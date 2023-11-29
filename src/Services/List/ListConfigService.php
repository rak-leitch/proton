<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;

class ListConfigService
{    
    /**
     * Get the list config for an entity
     * for use by the frontend.
     *
     * @aparam Entity $entity
     * 
     * @return mixed[]
    */
    public function getListConfig(Entity $entity) : array
    {
        $fieldsConfig = [];
        
        foreach($entity->getFields() as $field) {
            $fieldConfig = [];
            $fieldConfig['field_name'] = $field->getFieldName();
            $fieldConfig['sortable'] = $field->getSortable();
            $fieldsConfig[] = $fieldConfig;
        };
        
        return $fieldsConfig;
    }
}
