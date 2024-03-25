<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Internal\Field;
use Adepta\Proton\Field\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;
use Illuminate\Support\Collection;

final class FormConfigService
{    
    /**
     * Get the form config for an entity instance
     * for use by the frontend.
     *
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * 
     * @return array{
     *     config: array{
     *         fields: array<int, array{
     *             title: string, 
     *             key: string, 
     *             relatedEntityCode: string, 
     *             frontendType: string, 
     *             required: bool, 
     *             selectOptions: Collection<int, Model>
     *          }>
     *     }, 
     *     data: array<string, float|int|string|null>
     * }
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
            $fieldConfig['title'] = $field->getTitle();
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['relatedEntityCode'] = $field->getRelatedEntityCode();
            $fieldConfig['frontendType'] = $field->getFrontendType($displayContext)->value;
            $fieldConfig['required'] = $this->fieldRequired($field);
            $fieldConfig['selectOptions'] = $field->getSelectOptions();
            
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
     * @return array<string, float|int|string|null>
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
     * @param Field $field
     * 
     * @return bool
    */
    public function fieldRequired(Field $field) : bool
    {
        return (mb_strstr($field->getValidation(), 'required') === false) ? false : true; 
    }
}
