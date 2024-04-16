<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Field\Internal\HasMany;
use Adepta\Proton\Field\Internal\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Exceptions\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyRelation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use StdClass;

final class ListDataService
{ 
    /**
     * Constructor.
     * 
     * @param AuthorisationService $authService
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private AuthorisationService $authService,
        private EntityFactory $entityFactory,
    ) { }
    
    /**
     * Get the list data for an entity
     * for use by the frontend.
     *
     * @param Entity $entity 
     * @param Authenticatable $user
     * @param int $page 
     * @param int $itemsPerPage 
     * @param StdClass $requestQuery
     * 
     * @return array{
     *     totalRows: int, 
     *     data: array<int, array<string, float|int|string|bool|null>>, 
     *     permissions: array<int|string, array{
     *         update: bool, 
     *         view: bool, 
     *         delete: bool
     *     }>
     * }
    */
    public function getData(
        Entity $entity,
        ?Authenticatable $user, 
        int $page, 
        int $itemsPerPage, 
        StdClass $requestQuery
    ) : array
    {
        $response = [];
        $rowData = [];
        $permissions = [];
        $modelClass = $entity->getModelClass();
        $pkFieldName = $entity->getPrimaryKeyField()->getFieldName();
        $query = $modelClass::query();
        
        if($requestQuery->contextCode && $requestQuery->contextId) {
            $query = $this->getContextQuery(
                $requestQuery->contextCode, 
                $requestQuery->contextId, 
                $entity->getCode()
            );
        }
        
        $entity->getQueryFilter()($query);
        $this->addBelongsToRelations($entity, $query);
        $this->sort($query, $requestQuery, $entity);
        $totalRows = $query->count();
        $collection = $this->loadCollection($query, $page, $itemsPerPage);
        $listFields = $entity->getFields(
            displayContext: DisplayContext::INDEX
        );
        
        foreach($collection as $model) {            

            $row = [];

            foreach($listFields as $field) {
                $row[$field->getFieldName()] = $field->getProcessedValue($model);
            };
            
            $rowData[] = $row;
            
            $permissions[$model->{$pkFieldName}] = [
                'update' => $this->authService->canUpdate($user, $model),
                'view' => $this->authService->canView($user, $model),
                'delete' => $this->authService->canForceDelete($user, $model),
            ];
        }
        
        $response['totalRows'] = $totalRows;
        $response['data'] = $rowData;
        $response['permissions'] = $permissions;
        
        return $response;
    }
    
    /**
     * Get the list query for a contextual list.
     *
     * @param string $contextCode 
     * @param string $contextId 
     * @param string $entityCode 
     * 
     * @return HasManyRelation<Model>
    */
    private function getContextQuery(
        string $contextCode, 
        string $contextId, 
        string $entityCode
    ) : HasManyRelation
    {
        $contextEntity = $this->entityFactory->create($contextCode);
        
        $relationField = $contextEntity->getFields(
            displayContext: DisplayContext::DISPLAY, 
            fieldTypes: collect([HasMany::class]), 
            relatedEntityCode: $entityCode,
            onlyDisplayable: false
        )->first();
        
        if(!$relationField) {
            throw new ConfigurationException("Could not find context {$contextCode} relationship field");
        }

        $contextModel = $contextEntity->getLoadedModel($contextId);
        $relationMethod = $relationField->getRelationMethod($contextModel);
        
        return $contextModel->$relationMethod();
    }
    
    /**
     * Add on the BelongsTo relationships
     *
     * @param Entity $entity 
     * @param Builder $query
     * 
     * @return void
    */
    private function addBelongsToRelations(
        Entity $entity, 
        Builder $query
    ) : void
    {
        $belongsToFields = $entity->getFields(
            displayContext: DisplayContext::INDEX, 
            fieldTypes: collect([BelongsTo::class])
        );
        
        foreach($belongsToFields as $belongsToField) {
            
            $relationMethod = $belongsToField->getRelationMethod($entity->getModelClass());
            
            //Get all fields here so they can be 
            //used in policies without having to load the relationship.
            $query->with($relationMethod);
        }
    }
    
    /**
     * Perform a sort on the query
     *
     * @param Builder $query 
     * @param StdClass $requestQuery 
     * @param Entity $entity
     * 
     * @return void
    */
    private function sort(
        Builder $query, 
        StdClass $requestQuery,
        Entity $entity,
    ) : void
    {
        if($requestQuery->sortField && $requestQuery->sortOrder) {
            
            $sortField = $entity->getFields(
                displayContext: DisplayContext::INDEX,
                fieldName: $requestQuery->sortField,
            )->first();
            
            if($sortField && $sortField->getSortable()) {
                $query->orderBy($requestQuery->sortField, $requestQuery->sortOrder);
            } else {
                throw new RequestException("Invalid sort field: {$requestQuery->sortField}");
            }
        }
    }
    
    /**
     * Load the collection for use by the list.
     *
     * @param Builder $query 
     * @param int $page
     * @param int $itemsPerPage
     * 
     * @return Collection<int, Model>
    */
    private function loadCollection(
        Builder $query, 
        int $page,
        int $itemsPerPage
    ) : Collection
    {
        $skip = ($page - 1) * $itemsPerPage;
        return $query->skip($skip)->take($itemsPerPage)->get();
    }
}
