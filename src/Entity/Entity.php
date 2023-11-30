<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Support\Collection;
use Adepta\Proton\Contracts\Field\FieldContract;

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
}
