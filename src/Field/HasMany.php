<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

use Adepta\Proton\Field\Field;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Traits\ChecksRelationExistence;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

final class HasMany extends Field
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
        return null;
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
        $relationMethod = Str::camel(str::plural($this->fieldConfig->getFieldName()));
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
}
