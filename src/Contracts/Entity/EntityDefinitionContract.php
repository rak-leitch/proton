<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;

interface EntityDefinitionContract
{
    /**
     * Define and return the entity's configuration.
     * 
     * @return \Adepta\Proton\Contracts\Entity\EntityConfigContract $entityConfig
    */
    public function getEntityConfig() : EntityConfigContract;
}
