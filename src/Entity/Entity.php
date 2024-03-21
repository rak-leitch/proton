<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Support\Collection;
use Adepta\Proton\Field\Internal\Field;
use Illuminate\Support\Str;
use Adepta\Proton\Exceptions\ConfigurationException;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Internal\FieldFactory;
use Adepta\Proton\Field\FrontendType;
use Closure;

final class Entity
{    
    /**
     * @var Collection<int, Field> $fieldCollection
     */
    private Collection $fieldCollection;
    
    /**
     * Constructor
     * 
     * @param string $entityCode
     * @param EntityConfigContract $entityConfig
     * @param FieldFactory $fieldFactory
     *
    */
    public function __construct(
        private string $entityCode,
        private EntityConfigContract $entityConfig,
        private FieldFactory $fieldFactory
    )
    {
        $this->fieldCollection = collect();
        $this->initialiseFields();
        $this->validateEntityCode();
        $this->validateModelClass();
        $this->validateFieldExistence();
        $this->validatePrimaryKey();
        $this->validateNameField();
    }
    
    /**
     * Initialise the fields based on the field config.
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function initialiseFields() : void
    {
        foreach($this->entityConfig->getFields() as $fieldConfig) {
            $field = $this->fieldFactory->create($fieldConfig);
            $this->fieldCollection->push($field);
        } 
    }
    
    /**
     * Get the (filterable) fields for this entity.
     * 
     * @param DisplayContext $displayContext
     * @param ?Collection<int, string> $fieldTypes
     * @param ?string $fieldName = null
     * @param ?string $relatedEntityCode
     * @param ?bool $onlyDisplayable
     *
     * @return Collection<int, Field>
    */
    public function getFields(
        DisplayContext $displayContext, 
        ?Collection $fieldTypes = null,
        ?string $fieldName = null,
        ?string $relatedEntityCode = null,
        ?bool $onlyDisplayable = true,
    ) : Collection
    {
        $fields = $this->fieldCollection->filter(function ($field) use (
            $displayContext, 
            $fieldTypes, 
            $relatedEntityCode, 
            $onlyDisplayable, 
            $fieldName
        ) {
            $displayContextOk = $field->getDisplayContexts()->contains($displayContext);
            $fieldTypeOk = $fieldTypes ? $fieldTypes->contains($field->getClass()) : true;
            $fieldNameOk = ($fieldName !== null) ? ($field->getFieldName() === $fieldName) : true;
            $entityCodeOk = ($relatedEntityCode !== null) ? ($field->getRelatedEntityCode() === $relatedEntityCode) : true;
            $onlyDisplayableOk = $onlyDisplayable ? ($field->getFrontendType($displayContext) !== FrontendType::NONE) : true;
            return ($displayContextOk && $fieldTypeOk && $fieldNameOk && $entityCodeOk && $onlyDisplayableOk);
        });
        
        return $fields;
    }
    
    /**
     * Get the name field for this entity.
     *
     * @return Field
    */
    public function getNameField() : Field
    {
        $fields = $this->fieldCollection->filter(function ($field) {
            return $field->getIsNameField();
        });
        
        if(!$fields->first()) {
            throw new ConfigurationException('Could not find name field for '.$this->entityCode);
        } 
        
        return $fields->first();
    }
    
    /**
     * Get the code for this entity.
     *
     * @return string
    */
    public function getCode() : string
    {
        return $this->entityCode;
    }
    
    /**
     * Get the model for this entity.
     *
     * @return class-string<Model>
    */
    public function getModel() : string
    {
        return $this->entityConfig->getModel();
    }
    
    /**
     * Get loaded model for this entity.
     *
     * @param float|int|string $key
     * 
     * @return Model
    */
    public function getLoadedModel(float|int|string $key) : Model
    {
        $modelClass = $this->entityConfig->getModel();
        
        //Assuming our ID field is the same as the model's
        return $modelClass::findOrFail($key);
    }
    
    /**
     * Get the label for this entity.
     *
     * @return ?string
    */
    public function getLabel(bool $plural = false) : ?string
    {
        $label = Str::studly($this->entityCode);
        
        if($plural) {
            $label = Str::pluralStudly($label);
        }
        
        return preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $label);
    }
    
    /**
     * Get the primary key field for this entity.
     *
     * @return Field
    */
    public function getPrimaryKeyField() : Field
    {
        $primaryKeys = $this->fieldCollection->filter(function ($field, $key) {
            return $field->isPrimaryKey();
        });
        
        $pkField = $primaryKeys->first();
        
        if($pkField === null) {
            throw new ConfigurationException('Each entity must contain a primary key field');
        }
        
        return $pkField;
    }
    
    /**
     * Get the query filter for this entity.
     *
     * @return Closure
    */
    public function getQueryFilter() : Closure
    {
        return $this->entityConfig->getQueryFilter();
    }
    
    /**
     * Get the Studly code string for this entity.
     *
     * @return string
    */
    public function getStudlyCode() : string
    {
        return Str::studly($this->entityCode);
    }
    
    /**
     * Validate the entityCode
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function validateEntityCode() : void
    {        
        if(mb_strlen($this->entityCode) === 0) {
            throw new ConfigurationException('Entity code must be supplied with setCode()'); 
        }
    }
    
    /**
     * Validate the entityCode
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function validateModelClass() : void
    {
        if(!is_subclass_of($this->entityConfig->getModel(), Model::class)) {
            throw new ConfigurationException('Entity model must extend '.Model::class);
        }
    }
    
    /**
     * Check at least one field exists
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function validateFieldExistence() : void
    {
        if($this->fieldCollection->isEmpty()) {
            throw new ConfigurationException("Please provide at least one field when defining the {$this->entityCode} entity");
        }
    }
    
    /**
     * Check one primary key exists
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function validatePrimaryKey() : void
    {
        $primaryKeys = $this->fieldCollection->filter(function ($field, $key) {
            return $field->isPrimaryKey();
        });
        
        if($primaryKeys->count() !== 1) {
            throw new ConfigurationException('Each entity must contain a single primary key field');
        }
    }
    
    /**
     * Check one name field exists
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    private function validateNameField() : void
    {
        $nameFields = $this->fieldCollection->filter(function ($field, $key) {
            return $field->getIsNameField();
        });
        
        if($nameFields->count() !== 1) {
            throw new ConfigurationException('Each entity must contain a single name field');
        }
    }
}
