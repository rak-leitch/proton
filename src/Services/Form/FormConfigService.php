<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Field\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Services\EntityFactory;

final class FormConfigService
{    
    /**
     * Constructor.
     * 
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
    ) { }
    
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
            $fieldClass = $field->getClass();
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['related_entity_code'] = ($fieldClass === BelongsTo::class) ? $field->getRelatedEntityCode() : null;
            $fieldConfig['frontend_type'] = $field->getFrontendType($displayContext);
            $fieldConfig['required'] = $this->fieldRequired($field);
            
            if($field->getFrontendType($displayContext) === 'select') {
                $fieldConfig['select_options'] = $this->getSelectOptions($entity, $field);
            }
            
            $formConfig['config']['fields'][] = $fieldConfig;
            $formConfig['data'][$fieldName] = null;
        };
        
        return $formConfig;
    }
    
    /**
     * Get the select options for a field
     *
     * @param Entity $entity
     * @param FieldContract $field
     * 
     * @return mixed[]
    */
    public function getSelectOptions( 
        Entity $entity,
        FieldContract $field
    ) : array
    {
        $options = [];
        
        if($field->getClass() === BelongsTo::class) {
            $parentEntity = $this->entityFactory->create($field->getRelatedEntityCode());
            $keyField = $parentEntity->getPrimaryKeyField()->getFieldname();
            $nameField = $parentEntity->getNameField()->getFieldname();
            $modelClass = $parentEntity->getModel();
            $query = $modelClass::select("{$keyField} as value", "{$nameField} as title");
            $parentEntity->getQueryFilter()($query);
            $options = $query->get()->toArray();
        }
        
        return $options;
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
            $fieldName = $field->getFieldName();
            $formData[$fieldName] = $model->{$fieldName};
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
