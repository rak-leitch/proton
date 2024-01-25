<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Support\Collection;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Str;
use Adepta\Proton\Exceptions\ConfigurationException;

class Entity
{
    private EntityConfigContract $entityConfig;
    
    /**
     * Set the configuration object on the entity.
     * 
     * @param EntityConfigContract $entityConfig
     *
     * @return void
    */
    public function initialise(EntityConfigContract $entityConfig) : void
    {
        $this->validateConfig($entityConfig);
        $this->entityConfig = $entityConfig;
    }
    
    /**
     * Validate the configuration
     * 
     * @param EntityConfigContract $entityConfig
     * 
     * @throws ConfigurationException
     * 
     * @return void
     */
    public function validateConfig(EntityConfigContract $entityConfig) : void
    {
        $entityCode = $entityConfig->getCode();
        $entityModel = $entityConfig->getModel();
        $fields = $entityConfig->getFields();
        
        
        if(strlen($entityCode) === 0) {
            throw new ConfigurationException('Entity code must be supplied with setCode()'); 
        }
        
        if(strlen($entityModel) === 0) {
            throw new ConfigurationException('Entity model must be supplied with setModel()'); 
        }
        
        if($fields->isEmpty()) {
            throw new ConfigurationException("Please provide at least one field when defining the {$entityCode} entity");
        }
        
        $primaryKeys = $fields->filter(function ($field, $key) {
            return $field->isPrimaryKey();
        });
        
        if($primaryKeys->count() !== 1) {
            throw new ConfigurationException('Each entity must contain a single primary key field');
        }
    }
    
    /**
     * Get the fields for this entity.
     *
     * @return Collection<int, FieldContract>
    */
    public function getFields() : Collection
    {
        return $this->entityConfig->getFields();
    }
    
    /**
     * Get the code for this entity.
     *
     * @return string
    */
    public function getCode() : string
    {
        return $this->entityConfig->getCode();
    }
    
    /**
     * Get the model for this entity.
     *
     * @return string
    */
    public function getModel() : string
    {
        return $this->entityConfig->getModel();
    }
    
    /**
     * Get the label for this entity.
     *
     * @return ?string
    */
    public function getLabel(bool $plural = false) : ?string
    {
        $label = Str::studly($this->entityConfig->getCode());
        
        if($plural) {
            $label = Str::pluralStudly($label);
        }
        
        return preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $label);
    }
    
    /**
     * Get the primary key field for this entity.
     *
     * @return FieldContract
    */
    public function getPrimaryKeyField() : FieldContract
    {
        $primaryKeys = $this->entityConfig->getFields()->filter(function ($field, $key) {
            return $field->isPrimaryKey();
        });
        
        $pkField = $primaryKeys->first();
        
        if($pkField === null) {
            throw new ConfigurationException('Each entity must contain a single primary key field');
        }
        
        return $pkField;
    }
}
