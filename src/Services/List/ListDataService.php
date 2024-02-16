<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;
use Illuminate\Support\Facades\Auth;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Field\HasMany;
use Adepta\Proton\Field\BelongsTo;
use ReflectionClass;
use Adepta\Proton\Exceptions\ConfigurationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyRelation;
use Illuminate\Foundation\Auth\User;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
     * @param User $user
     * @param int $page 
     * @param int $itemsPerPage 
     * @param string $sortBy
     * @param ?string $contextCode
     * @param ?int $contextId
     * 
     * @return mixed[]
    */
    public function getData(
        Entity $entity,
        ?User $user, 
        int $page, 
        int $itemsPerPage, 
        string $sortBy,
        ?string $contextCode,
        ?int $contextId
    ) : array
    {
        $response = [];
        $rowData = [];
        $permissions = [];
        $displayContext = DisplayContext::INDEX;
        $modelClass = $entity->getModel();
        $pkFieldName = $entity->getPrimaryKeyField()->getFieldName();
        $query = $modelClass::query();
        
        if($contextCode && $contextId) {
            $displayContext = DisplayContext::VIEW;
            $query = $this->getContextQuery(
                $contextCode, 
                $contextId, 
                $entity->getCode()
            );
        }
        
        $entity->getQueryFilter()($query);
        $this->addBelongsToFields($entity, $displayContext, $query);
        
        $totalRows = $query->count();
        $skip = ($page - 1) * $itemsPerPage;
        $collection = $query->skip($skip)->take($itemsPerPage)->get();
        
        foreach($collection as $model) {            

            $row = [];

            foreach($entity->getFields($displayContext) as $field) {
                $row[$field->getFieldName()] = $this->getFieldValue($field, $model);
            };
            
            $rowData[] = $row;
            
            $permissions[$model->{$pkFieldName}] = [
                'update' => $this->authService->canUpdate($user, $model),
                'view' => $this->authService->canView($user, $model),
                'delete' => $this->authService->canDelete($user, $model),
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
     * @param int $contextId 
     * @param string $entityCode 
     * 
     * @return HasManyRelation<Model>
    */
    private function getContextQuery(
        string $contextCode, 
        int $contextId, 
        string $entityCode
    ) : HasManyRelation
    {
        $contextEntity = $this->entityFactory->create($contextCode);
        
        $relationshipField = $contextEntity->getFields(
            DisplayContext::VIEW, 
            collect([HasMany::class]), 
            $entityCode
        )->first();
        
        if(!$relationshipField) {
            throw new ConfigurationException("Could not find context {$contextCode} relationship field");
        }
        
        $contextModelClass = $contextEntity->getModel();
        $contextModel = $contextModelClass::findOrFail($contextId);
        $relationshipMethod = $relationshipField->getCamelName(true);
        $reflection = new ReflectionClass($contextModel);
        
        if(!$reflection->hasMethod($relationshipMethod)) {
            $error = "Could not find HasMany method {$relationshipMethod} for context entity {$contextCode}";
            throw new ConfigurationException($error);
        }
        
        return $contextModel->$relationshipMethod();
    }
    
    /**
     * Get the field value given a
     * field and model.
     *
     * @param FieldContract $field 
     * @param Model $model
     * 
     * @return string
    */
    private function getFieldValue(FieldContract $field, Model $model)
    {
        $fieldValue = null;
        $reflection = new ReflectionClass($field);
        $fieldName = $field->getFieldName();
        
        if($reflection->getName() === BelongsTo::class) {
            $parentEntity = $this->entityFactory->create($field->getSnakeName());
            $parentNameField = $parentEntity->getNameField()->getFieldName();
            $relationName = $field->getCamelName(); //TODO: Check this
            
            if ($model->relationLoaded($relationName)) {
                $relation = $model->{$relationName};
                $fieldValue = $relation->{$parentNameField};
            } else {
                $error = "Could not find BelongsTo data for relation {$relationName}";
                throw new ConfigurationException($error);
            }
        } else {
            $fieldValue = $model->{$fieldName};
        }
        
        return $fieldValue;
    }
    
    /**
     * Join on the BelongsTo entities
     *
     * @param Entity $entity
     * @param DisplayContext $displayContext 
     * @param Builder &$query
     * 
     * @return void
    */
    private function addBelongsToFields(
        Entity $entity, 
        DisplayContext $displayContext,
        Builder &$query
    ) : void
    {
        foreach($entity->getFields($displayContext, collect([BelongsTo::class])) as $field) {
            
            $relationshipMethod = $field->getCamelName();
            $reflection = new ReflectionClass($entity->getModel());
        
            if(!$reflection->hasMethod($relationshipMethod)) {
                $error = "Could not find BelongsTo method {$relationshipMethod} for entity {$entity->getCode()}";
                throw new ConfigurationException($error);
            }
            
            $query->with($relationshipMethod);
        }
    }
}
