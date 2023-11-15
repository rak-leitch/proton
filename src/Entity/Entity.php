<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;

class Entity
{
    /**
     * Set the configuration object on the entity.
     * 
     * @param \Adepta\Proton\Contracts\Entity\EntityConfigContract $entityConfig
     *
     * @return void
    */
    public function initialise(EntityConfigContract $entityConfig) : void
    {
    
    }
}
