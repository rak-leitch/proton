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
use Adepta\Proton\Services\Utilities\RelationService;

final class DisplayConfigService
{    
    /**
     * Constructor.
     * 
     * @param EntityFactory $entityFactory
     * @param RelationService $relationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private RelationService $relationService,
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
        
        $fields = $entity->getFields(
            displayContext: DisplayContext::VIEW,
        );
        
        foreach($fields as $field) {
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
    private function getValue(FieldContract $field, Model $model) : string|int|float|null
    {    
        $fieldValue = null;       
        
        if($field->getClass() === BelongsTo::class) {
            $fieldValue = $this->relationService->getBelongsToValue($model, $field);
        } else {
            $fieldName = $field->getFieldName();
            $fieldValue = $model->{$fieldName};
        }
        
        return $fieldValue;
    }
}
