<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Support\Collection;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Str;

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
        $entityConfig->validate();
        $this->entityConfig = $entityConfig;
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
}
