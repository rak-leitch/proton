<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;

final class ListConfigService
{    
    /**
     * Constructor.
     * 
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the list config for an entity
     * for use by the frontend.
     *
     * @param ?Authenticatable $user
     * @param Entity $entity
     * 
     * @return array{
     *     fields: array<int, array{
     *         title: string, 
     *         key: string,     
     *         sortable: bool
     *     }>, 
     *     primaryKey: string, 
     *     canCreate: bool, 
     *     initialPageSize: int,
     *     entityLabel: string, 
     *     version: string, 
     *     pageSizeOptions: array<int, array{
     *         value: int, 
     *         title: string
     *     }>
     * }
    */
    public function getListConfig(?Authenticatable $user, Entity $entity) : array
    {
        $listConfig = [];
        $listConfig['fields'] = [];
        $listConfig['primaryKey'] = $entity->getPrimaryKeyField()->getFieldName(); 
        $listConfig['canCreate'] = $this->authorisationService->canCreate($user, $entity);
        $listConfig['entityLabel'] = $entity->getLabel();
        $listConfig['initialPageSize'] = 5;
        $listConfig['version'] = Carbon::now()->toJSON();
        
        $listConfig['pageSizeOptions'] = [
            ['value' => 3, 'title' => '3'],
            ['value' => 5, 'title' => '5'],
            ['value' => 10, 'title' => '10'],
            ['value' => 20, 'title' => '20'],
        ];
        
        foreach($entity->getFields(DisplayContext::INDEX) as $field) {
            $fieldConfig = [];
            $fieldConfig['title'] = $field->getTitle();
            $fieldConfig['key'] = $field->getFieldName();
            $fieldConfig['sortable'] = $field->getSortable();
            $listConfig['fields'][] = $fieldConfig;
        };
        
        //Actions column
        $listConfig['fields'][] = [
            'title' => 'Actions',
            'key' => 'actions',
            'sortable' => false,
        ];
        
        return $listConfig;
    }
}
