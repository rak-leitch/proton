<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;

class FormConfigService
{    
    /**
     * Get the form config for an entity instance
     * for use by the frontend.
     *
     * @param Entity $entity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getFormConfig(Entity $entity, Model $model) : array
    {
        $formConfig = [];
        $formConfig['config'] = [];
        $formConfig['config']['fields'] = [];
        $formConfig['data'] = [];
         
        
        foreach($entity->getFields() as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['type'] = $field->getFrontendType();
            $formConfig['config']['fields'][] = $fieldConfig;
            $formConfig['data'][$fieldName] = $model->{$fieldName};
        };
        
        return $formConfig;
    }
}
