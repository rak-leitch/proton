<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Field;

use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface FieldContract
{
    /**
     * Get the class name of a field for
     * comparision.
     * 
     * @return string
     */
    public function getClass() : string;
    
    /**
     * Static convenience method to create and return 
     * an instance of a field.
     *
     * @param string $fieldName 
     * 
     * @return self
     */
    public static function create(string $fieldName) : self;
    
    /**
     * Set the field's sortable property.
     * 
     * @return self
     */
    public function sortable() : self;
    
    /**
     * Set the field's name property.
     * 
     * @return self
     */
    public function name() : self;
    
    /**
     * Get the field's name.
     * 
     * @return string
     */
    public function getFieldName() : string;
    
    /**
     * Get whether the field is sortable.
     * 
     * @return bool
     */
    public function getSortable() : bool;
    
    /**
     * Get whether the field is the name field.
     * 
     * @return bool
     */
    public function getIsNameField() : bool;
    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return ?string
     */
    public function getFrontendType(DisplayContext $displayContext) : ?string;
    
    /**
     * Indicate whether this field is a primary key.
     * 
     * @return bool
     */
    public function isPrimaryKey() : bool;
    
    /**
     * Set validation for this field
     * 
     * @return self
     */
    public function setValidation(string $validation) : self;
    
    /**
     * Get validation for this field
     * 
     * @return string
     */
    public function getValidation() : string;
    
    /**
     * Get the display contexts for this field
     * 
     * @return Collection<int, DisplayContext>
     */
    public function getDisplayContexts() : Collection;
    
    /**
     * Get the field's related entity code.
     * 
     * @return string
     */
    public function getRelatedEntityCode() : string;
    
    /**
     * Get the field's relation method name.
     * 
     * @param Model|class-string $model
     * 
     * @return string
     */
    public function getRelationMethod(Model|string $model) : string;
    
    /**
     * Get the field's raw value.
     * 
     * @param Model $model
     * 
     * @return string|int|float|null
     */
    public function getRawValue(Model $model) : string|int|float|null;
    
    /**
     * Get the field's processed value for use by the frontend.
     * 
     * @param Model $model
     * 
     * @return string|int|float|null
     */
    public function getProcessedValue(Model $model) : string|int|float|null;
    
    /**
     * Get the field's select options.
     * 
     * @return Collection<int, Model>
     */
    public function getSelectOptions() : Collection;
}
