<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Field;

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
}
