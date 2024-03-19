<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Field;

use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface FieldConfigContract
{    
    /**
     * Indication of the field class this config 
     * object corresponds to
     * 
     * @return class-string
     */
    public static function getFieldClass() : string;
    
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
     * Set validation for this field
     * 
     * @return self
     */
    public function setValidation(string $validation) : self;
    
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
}
