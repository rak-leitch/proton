<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Exceptions\ConfigurationException;

abstract class Field implements FieldContract
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
     * Get the class name of a field for
     * comparision.
     * 
     * @return string
     */
    public function getClass() : string
    {
        return static::class;
    }
    
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
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return ?string
     */
    abstract public function getFrontendType(DisplayContext $displayContext) : ?string;
    
    /**
     * Indicate whether this field is a primary key.
     * 
     * @return bool
     */
    public function isPrimaryKey() : bool
    {
        return false;
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
    
    /**
     * Get the field's related entity code.
     * 
     * @return string
     */
    public function getRelatedEntityCode() : string
    {
        return '';
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
        return '';
    }
    
    /**
     * Get the field's raw value.
     * 
     * @param Model $model
     * 
     * @return string|int|float|null
     */
    public function getRawValue(Model $model) : string|int|float|null
    {
        $value = null;
        $fieldName = $this->getFieldName();
        
        if(array_key_exists($fieldName, $model->getAttributes())) {
            $value = $model->{$fieldName};
        } else {
            throw new ConfigurationException("Could not find {$fieldName} value");
        }
        
        return $value;
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
        return $this->getRawValue($model);
    }
    
    /**
     * Get the field's select options.
     * 
     * @return Collection<int, Model>
     */
    public function getSelectOptions() : Collection
    {
        return collect([]);
    }
}
