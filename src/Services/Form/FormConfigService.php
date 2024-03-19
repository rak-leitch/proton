<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Field\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;

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
        
        $fields = $entity->getFields(
            displayContext: $displayContext
        );
        
        foreach($fields as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['related_entity_code'] = $field->getRelatedEntityCode();
            $fieldConfig['frontend_type'] = $field->getFrontendType($displayContext);
            $fieldConfig['required'] = $this->fieldRequired($field);
            $fieldConfig['select_options'] = $field->getSelectOptions();
            
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
        
        $fields = $entity->getFields(
            displayContext: $displayContext
        );
        
        foreach($fields as $field) {
            $formData[$field->getFieldName()] = $field->getRawValue($model);
        }
        
        return $formData;
    }
    
    /**
     * Check if a field is required. This is somewhat naive
     * at the moment; it does not evaluate rules like required_if 
     * etc
     *
     * @param FieldContract $field
     * 
     * @return bool
    */
    public function fieldRequired(FieldContract $field) : bool
    {
        return (mb_strstr($field->getValidation(), 'required') === false) ? false : true; 
    }
}
