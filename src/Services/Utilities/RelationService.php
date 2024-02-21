<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Utilities;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Services\EntityFactory;

final class RelationService
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
     * Get the validated name of a relation method 
     * on a model class.
     * 
     * @param Model|class-string $model 
     * @param FieldContract $field
     * @param bool $plural
     * 
     * @return string
    */
    public function getRelationMethod(
        Model|string $model, 
        FieldContract $field, 
        bool $plural = false
    ) : string 
    {
        $relationMethod = $field->getRelationMethod($plural);
        $reflection = new ReflectionClass($model);
        
        if(!$reflection->hasMethod($relationMethod)) {
            $error = "Could not find relation method {$relationMethod}.";
            throw new ConfigurationException($error);
        }
        
        return $relationMethod;
    }
    
    /**
     * Get the validated name of a relation method 
     * on a model class.
     * 
     * @param Model|class-string $model 
     * @param FieldContract $field
     * 
     * @return string|int|float|null
    */
    public function getBelongsToValue(
        Model|string $model, 
        FieldContract $field, 
    ) : string|int|float|null
    {
        $parentEntity = $this->entityFactory->create($field->getRelatedEntityCode());
        $parentNameField = $parentEntity->getNameField()->getFieldName();
        $relationName = $this->getRelationMethod($model, $field);
        $relation = $model->{$relationName};
        return $relation->{$parentNameField};
    }
}
