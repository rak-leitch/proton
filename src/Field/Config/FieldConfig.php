<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Config;

use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;

abstract class FieldConfig implements FieldConfigContract
{
    protected string $fieldName;
    protected bool $sortable;
    protected bool $nameField;
    protected string $validation;
    
    /**
     * @var Collection<int, DisplayContext> $displayContexts
     */
    protected Collection $displayContexts;
    
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
        $this->nameField = false;
        $this->validation = '';
        
        $this->setInitialDisplayContexts();
    }
    
    /**
     * Indication of the field class this config 
     * object corresponds to
     * 
     * @return class-string
     */
    abstract public static function getFieldClass() : string;
    
    /**
     * Set initial display contexts for each field 
     * type. 
     * 
     * @return void
     */
    abstract protected function setInitialDisplayContexts() : void;
    
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
        return app()->make(static::class, [
            'fieldName' => $fieldName
        ]);
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
     * Set the field's name property.
     * 
     * @return self
     */
    public function name() : self
    {
        $this->nameField = true;
        return $this;
    }
    
    /**
     * Set validation for this field
     * 
     * @return self
     */
    public function setValidation(string $validation) : self
    {
        $this->validation = $validation;
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
     * Get whether the field is the name field.
     * 
     * @return bool
     */
    public function getIsNameField() : bool
    {
        return $this->nameField;
    }
    
    /**
     * Get validation for this field
     * 
     * @return string
     */
    public function getValidation() : string
    {
        return $this->validation;
    }
    
    /**
     * Get the display contexts for this field
     * 
     * @return Collection<int, DisplayContext>
     */
    public function getDisplayContexts() : Collection
    {
        return $this->displayContexts;
    }
    
}
