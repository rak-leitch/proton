<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Field;

use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;

interface FieldContract
{
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
     * Get the field's frontend display type.
     * 
     * @return string
     */
    public function getFrontendType() : string;
    
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
    public function getValidation();
    
    /**
     * Get the display contexts for this field
     * 
     * @return Collection<int, DisplayContext>
     */
    public function getDisplayContexts() : Collection;
}
