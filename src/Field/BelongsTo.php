<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Traits\ChecksRelationExistence;
use Illuminate\Support\Str;
use Adepta\Proton\Services\EntityFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class BelongsTo extends Field
{    
    use ChecksRelationExistence;
    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return ?string
     */
    public function getFrontendType(DisplayContext $displayContext) : ?string
    {
        return $displayContext->mutatingContext() ? 'select' : 'text';
    }
    
    /**
     * Set initial display contexts for this field
     * type.
     * 
     * @return void
     */
    protected function setInitialDisplayContexts() : void
    {
        $this->displayContexts = collect([
            DisplayContext::CREATE,
            DisplayContext::UPDATE,
            DisplayContext::VIEW,
            DisplayContext::INDEX,
        ]);
    }
    
    /**
     * Get the field's name. Guess this from the 
     * entity code provided.
     * 
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldName.'_id';
    }
    
    /**
     * Get the field's processed value for use by the frontend.
     * 
     * @param Model $model
     * 
     * @return string|int|float|null
     */
    public function getProcessedValue(Model $model) : string|int|float|null
    {
        $entityFactory = app()->make(EntityFactory::class);
        $parentEntity = $entityFactory->create($this->getRelatedEntityCode());
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
        $relationMethod = Str::camel($this->fieldName);
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
        return $this->fieldName;
    }
    
    /**
     * Get the field's select options.
     * 
     * @return Collection<int, Model>
     */
    public function getSelectOptions() : Collection
    {
        $entityFactory = app()->make(EntityFactory::class);
        $parentEntity = $entityFactory->create($this->getRelatedEntityCode());
        $keyField = $parentEntity->getPrimaryKeyField()->getFieldname();
        $nameField = $parentEntity->getNameField()->getFieldname();
        $modelClass = $parentEntity->getModel();
        $query = $modelClass::select("{$keyField} as value", "{$nameField} as title");
        $parentEntity->getQueryFilter()($query);
        return $query->get();
    }
}
