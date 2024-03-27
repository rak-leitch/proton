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
    
    private Field $primaryKeyField;
    private Field $nameField;
    
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
        $this->validateSetup();
        $this->initPrimaryKeyField();
        $this->initNameField();
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
            $displayContextFound = $field->getDisplayContexts()->contains($displayContext);
            
            $fieldTypeFound = ($fieldTypes !== null) ? 
                $fieldTypes->contains($field->getClass()) 
                : true;
            
            $fieldNameFound = ($fieldName !== null) ? 
                ($field->getFieldName() === $fieldName) 
                : true;
                
            $entityCodeFound = ($relatedEntityCode !== null) ? 
                ($field->getRelatedEntityCode() === $relatedEntityCode) 
                : true;
                
            $onlyDisplayableFound = $onlyDisplayable ? 
                ($field->getFrontendType($displayContext) !== FrontendType::NONE) 
                : true;
                
            return (
                $displayContextFound && 
                $fieldTypeFound && 
                $fieldNameFound && 
                $entityCodeFound && 
                $onlyDisplayableFound
            );
        });
        
        return $fields;
    }
    
    /**
     * Get the primary key field for this entity.
     *
     * @return Field
    */
    public function getPrimaryKeyField() : Field
    {
        return $this->primaryKeyField;
    }
    
    /**
     * Get the name field for this entity.
     *
     * @return Field
    */
    public function getNameField() : Field
    {
        return $this->nameField;
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
    public function getModelClass() : string
    {
        return $this->entityConfig->getModelClass();
    }
    
    /**
     * Get loaded model for this entity.
     *
     * @param int|string $key
     * 
     * @return Model
    */
    public function getLoadedModel(int|string $key) : Model
    {
        $modelClass = $this->entityConfig->getModelClass();
        
        //Assuming our ID field is the same as the model's
        return $modelClass::findOrFail($key);
    }
    
    /**
     * Get the label for this entity.
     *
     * @return string
    */
    public function getLabel(bool $plural = false) : string
    {
        $label = Str::of($this->entityCode)->replace('_', ' ');
        
        if($plural) {
            $label = $label->plural();
        }
        
        return $label->title()->toString();
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
     * Validate the setup of the entity.
     *
     * @return void
    */
    private function validateSetup() : void
    {
        $this->validateEntityCode();
        $this->validateModelClass();
        $this->validateFieldExistence();
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
            throw new ConfigurationException('Non-empty entity code must be supplied in config'); 
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
        if(!is_subclass_of($this->entityConfig->getModelClass(), Model::class)) {
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
     * Initialise the primary key field for this entity.
     *
     * @return void
    */
    private function initPrimaryKeyField() : void
    {
        $primaryKeys = $this->fieldCollection->filter(function ($field) {
            return $field->isPrimaryKey();
        });
        
        $pkField = $primaryKeys->first();
        
        if($pkField === null) {
            throw new ConfigurationException('Could not find primary key field for '.$this->entityCode);
        }
        
        if($primaryKeys->count() > 1) {
            throw new ConfigurationException('Each entity must contain a single primary key field');
        }
        
        $this->primaryKeyField = $pkField;
    }
    
    /**
     * Initialise the name field for this entity.
     *
     * @return void
    */
    private function initNameField() : void
    {
        $nameFields = $this->fieldCollection->filter(function ($field) {
            return $field->getIsNameField();
        });
        
        $nameField = $nameFields->first();
        
        if(!$nameField) {
            throw new ConfigurationException('Could not find name field for '.$this->entityCode);
        } 
        
        if($nameFields->count() > 1) {
            throw new ConfigurationException('Each entity must contain a single name field');
        }
        
        $this->nameField = $nameField;
    }
}
