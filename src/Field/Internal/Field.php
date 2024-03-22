<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;
use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Illuminate\Support\Str;
use Adepta\Proton\Field\FrontendType;

abstract class Field
{
    /**
     * Constructor
     * 
     * @param FieldConfigContract $fieldConfig
     * 
     * @return string
     */
    public function __construct(
        protected FieldConfigContract $fieldConfig
    ) { }
    
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
     * Get the field's name.
     * 
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldConfig->getFieldName();
    }
    
    /**
     * Get the field's title.
     * 
     * @return string
     */
    public function getTitle() : string
    {
        $title = null;
        $configuredTitle = $this->fieldConfig->getTitle();
        
        if($configuredTitle) {
            $title = $configuredTitle;
        } else {
            $fieldName = $this->fieldConfig->getFieldName();
            $title = Str::of($fieldName)->replace('_', ' ')->title()->toString();
        }
        
        return $title;
    }
    
    /**
     * Get whether the field is sortable.
     * 
     * @return bool
     */
    public function getSortable() : bool
    {
        return $this->fieldConfig->getSortable();
    }
    
    /**
     * Get whether the field is the name field.
     * 
     * @return bool
     */
    public function getIsNameField() : bool
    {
        return $this->fieldConfig->getIsNameField();
    }
    
    /**
     * Get validation for this field
     * 
     * @return string
     */
    public function getValidation() : string
    {
        return $this->fieldConfig->getValidation();
    }
    
    /**
     * Get the display contexts for this field
     * 
     * @return Collection<int, DisplayContext>
     */
    public function getDisplayContexts() : Collection
    {
        return $this->fieldConfig->getDisplayContexts();
    }
    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return FrontendType
     */
    abstract public function getFrontendType(DisplayContext $displayContext) : FrontendType;
    
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
