<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;
use Adepta\Proton\Contracts\Field\FieldContract;

abstract class Field implements FieldContract
{
    private string $fieldName;
    private bool $sortable;
    
    /**
     * Constructor
     *
     * @param string $fieldName 
     * 
     * @return void
     */
    final public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
        $this->sortable = false;
    }
    
    /**
     * Static convenience method to create and return 
     * an instance of a field.
     *
     * @param string $fieldName 
     * 
     * @return self
     */
    public static function create(string $fieldName) : self
    {
        return new static($fieldName);
    }
    
    /**
     * Set the field's sortable property.
     * 
     * @return self
     */
    public function sortable() : self
    {
        $this->sortable = true;
        return $this;
    }
    
    /**
     * Get the field's name.
     * 
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldName;
    }
    
    /**
     * Get whether the field is sortable.
     * 
     * @return bool
     */
    public function getSortable() : bool
    {
        return $this->sortable;
    }
    
    /**
     * Get the field's frontend display type.
     * 
     * @return string
     */
    abstract public function getFrontendType() : string;
}
