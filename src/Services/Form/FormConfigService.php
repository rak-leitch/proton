<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;

final class FormConfigService
{    
    /**
     * Get the form config for an entity instance
     * for use by the frontend.
     *
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * 
     * @return mixed[]
    */
    public function getFormConfig(
        DisplayContext $displayContext, 
        Entity $entity
    ) : array
    {
        $formConfig = [];
        $formConfig['config'] = [];
        $formConfig['config']['fields'] = [];
        $formConfig['data'] = [];
         
        
        foreach($entity->getFields($displayContext) as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['type'] = $field->getFrontendType();
            $formConfig['config']['fields'][] = $fieldConfig;
            $formConfig['data'][$fieldName] = null;
        };
        
        return $formConfig;
    }
    
    /**
     * Get the form field data for an entity instance
     * for use by the frontend.
     *
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getFormData(
        DisplayContext $displayContext, 
        Entity $entity,
        Model $model
    ) : array
    {
        $formData = [];
        
        foreach($entity->getFields($displayContext) as $field) {
            $fieldName = $field->getFieldName();
            $formData[$fieldName] = $model->{$fieldName};
        }
        
        return $formData;
    }
}
