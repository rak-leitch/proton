<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;

use Adepta\Proton\Field\Internal\Field;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Traits\ChecksRelationExistence;
use Illuminate\Support\Str;
use Adepta\Proton\Entity\EntityFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Adepta\Proton\Field\FrontendType;

final class BelongsTo extends Field
{    
    use ChecksRelationExistence;
    
    /**
     * Constructor
     * 
     * @param FieldConfigContract $fieldConfig
     * @param EntityFactory $entityFactory
     * 
     * @return string
     */
    public function __construct(
        FieldConfigContract $fieldConfig,
        protected EntityFactory $entityFactory,
    ) { 
        parent::__construct($fieldConfig);    
    }
    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return FrontendType
     */
    public function getFrontendType(DisplayContext $displayContext) : FrontendType
    {
        return $displayContext->mutatingContext() ? FrontendType::SELECT : FrontendType::TEXT;
    }
    
    /**
     * Get the field's name. Guess this from the 
     * field name provided.
     * 
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldConfig->getFieldName().'_id';
    }
    
    /**
     * Get the field's processed value for use by the frontend.
     * 
     * @param Model $model
     * 
     * @return float|int|string|bool|null
     */
    public function getProcessedValue(Model $model) : float|int|string|bool|null
    {
        $parentEntity = $this->entityFactory->create($this->getRelatedEntityCode());
        $parentNameField = $parentEntity->getNameField();
        $relationName = $this->getRelationMethod($model);
        $relation = $model->{$relationName};
        return $relation ? $parentNameField->getProcessedValue($relation) : null;
    }
    
    /**
     * Get the field's relation method name.
     * 
     * @param Model|class-string $model
     * 
     * @return string
     */
    public function getRelationMethod(Model|string $model) : string
    {
        $relationMethod = Str::camel($this->fieldConfig->getFieldName());
        $this->checkModelRelation($model, $relationMethod);
        return $relationMethod;
    }
    
    /**
     * Get the field's related entity code.
     * 
     * @return string
     */
    public function getRelatedEntityCode() : string
    {
        return $this->fieldConfig->getFieldName();
    }
    
    /**
     * Get the field's select options.
     * 
     * @return Collection<int, Model>
     */
    public function getSelectOptions() : Collection
    {
        $parentEntity = $this->entityFactory->create($this->getRelatedEntityCode());
        $keyField = $parentEntity->getPrimaryKeyField()->getFieldname();
        $nameField = $parentEntity->getNameField()->getFieldname();
        $modelClass = $parentEntity->getModelClass();
        $query = $modelClass::select("{$keyField} as value", "{$nameField} as title");
        $parentEntity->getQueryFilter()($query);
        return $query->get();
    }
}
