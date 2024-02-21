<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Support\Collection;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Str;
use Adepta\Proton\Exceptions\ConfigurationException;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Closure;

final class Entity
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
        $entityClass = $entityConfig->getModel();
        $fields = $entityConfig->getFields();
        
        if(mb_strlen($entityCode) === 0) {
            throw new ConfigurationException('Entity code must be supplied with setCode()'); 
        }
        
        if($entityClass === Model::class) {
            throw new ConfigurationException('Entity model must be supplied with setModel()'); 
        }
        
        if(!is_subclass_of($entityClass, Model::class)) {
            throw new ConfigurationException('Entity model must extend '.Model::class);
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
        
        $nameFields = $fields->filter(function ($field, $key) {
            return $field->getIsNameField();
        });
        
        if($nameFields->count() !== 1) {
            throw new ConfigurationException('Each entity must contain a single name field');
        }
    }
    
    /**
     * Get the (filterable) fields for this entity.
     * 
     * @param DisplayContext $displayContext
     * @param ?Collection<int, string> $fieldTypes
     * @param ?string $relatedEntityCode
     * @param ?bool $onlyDisplayable
     *
     * @return Collection<int, FieldContract>
    */
    public function getFields(
        DisplayContext $displayContext, 
        ?Collection $fieldTypes = null,
        ?string $relatedEntityCode = null,
        ?bool $onlyDisplayable = true,
    ) : Collection
    {
        $fields = $this->entityConfig->getFields();
        
        $fields = $fields->filter(function ($field) use ($displayContext, $fieldTypes, $relatedEntityCode, $onlyDisplayable) {
            $displayContextOk = $field->getDisplayContexts()->contains($displayContext);
            $fieldTypeOk = $fieldTypes ? $fieldTypes->contains($field->getClass()) : true;
            $entityCodeOk = ($relatedEntityCode !== null) ? ($field->getRelatedEntityCode() === $relatedEntityCode) : true;
            $onlyDisplayableOk = $onlyDisplayable ? ($field->getFrontendType($displayContext) !== null) : true;
            return ($displayContextOk && $fieldTypeOk && $entityCodeOk && $onlyDisplayableOk);
        });
        
        return $fields;
    }
    
    /**
     * Get the name field for this entity.
     *
     * @return FieldContract
    */
    public function getNameField() : FieldContract
    {
        $fields = $this->entityConfig->getFields();
        
        $fields = $fields->filter(function ($field) {
            return $field->getIsNameField();
        });
        
        if(!$fields->first()) {
            throw new ConfigurationException('Could not find name field for '.$this->entityConfig->getCode());
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
        return $this->entityConfig->getCode();
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
        return Str::studly($this->entityConfig->getCode());
    }
}
