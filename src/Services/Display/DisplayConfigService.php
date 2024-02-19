<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Display;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Contracts\Field\FieldContract;
use ReflectionClass;
use Adepta\Proton\Field\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Services\EntityFactory;

final class DisplayConfigService
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
     * @param Entity $entity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getDisplayConfig(Entity $entity, Model $model) : array
    {
        $displayConfig = [];
        $displayConfig['fields'] = [];
        
        foreach($entity->getFields(DisplayContext::VIEW) as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['frontend_type'] = $field->getFrontendType(DisplayContext::VIEW);
            $fieldConfig['value'] = $this->getValue($field, $model);
            
            $displayConfig['fields'][] = $fieldConfig;
        };
        
        return $displayConfig;
    }
    
    /**
     * Get the display value of the field.
     *
     * @param FieldContract $field
     * @param Model $model
     * 
     * @return string|int|float|null
    */
    private function getValue(FieldContract $field, Model$model)
    {    
        $fieldValue = null;       
        $reflection = new ReflectionClass($field);
        $fieldName = $field->getFieldName();
        
        if($reflection->getName() === BelongsTo::class) {
            $parentEntity = $this->entityFactory->create($field->getSnakeName());
            $parentNameField = $parentEntity->getNameField()->getFieldName();
            $relationName = $field->getCamelName();
            $modelReflection = new ReflectionClass($model);
        
            if(!$modelReflection->hasMethod($relationName)) {
                $error = "Could not find BelongsTo method {$relationName}";
                throw new ConfigurationException($error);
            }
            
            $relation = $model->{$relationName};
            $fieldValue = $relation->{$parentNameField};
        } else {
            $fieldValue = $model->{$fieldName};
        }
        
        return $fieldValue;
    }
}
