<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Foundation\Auth\User;

class ListConfigService
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
     * @param ?User $user
     * @param Entity $entity
     * 
     * @return mixed[]
    */
    public function getListConfig(?User $user, Entity $entity) : array
    {
        $listConfig = [];
        $listConfig['fields'] = [];
        $listConfig['primary_key'] = $entity->getPrimaryKeyField()->getFieldName(); 
        $listConfig['can_create'] = $this->authorisationService->canCreate($user, $entity);
        $listConfig['entity_label'] = $entity->getLabel();
        
        //TODO: Need indication of view/index context here?
        foreach($entity->getFields(DisplayContext::INDEX) as $field) {
            $fieldConfig = [];
            $fieldConfig['title'] = $field->getFieldName();
            $fieldConfig['key'] = $field->getFieldName();
            $fieldConfig['sortable'] = $field->getSortable();
            $listConfig['fields'][] = $fieldConfig;
        };
        
        return $listConfig;
    }
}
