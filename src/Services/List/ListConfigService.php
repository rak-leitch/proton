<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;

class ListConfigService
{    
    /**
     * Get the list config for an entity
     * for use by the frontend.
     *
     * @param Entity $entity
     * 
     * @return mixed[]
    */
    public function getListConfig(Entity $entity) : array
    {
        $listConfig = [];
        $listConfig['fields'] = [];
        $listConfig['primary_key'] = $entity->getPrimaryKeyField()->getFieldName(); 
        
        foreach($entity->getFields() as $field) {
            $fieldConfig = [];
            $fieldConfig['title'] = $field->getFieldName();
            $fieldConfig['key'] = $field->getFieldName();
            $fieldConfig['sortable'] = $field->getSortable();
            $listConfig['fields'][] = $fieldConfig;
        };
        
        return $listConfig;
    }
}
