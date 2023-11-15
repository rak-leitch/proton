<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;

class TaskDefinition implements EntityDefinitionContract
{
    /**
     * Constructor
     * 
     * @param \Adepta\Proton\Contracts\Entity\EntityConfigContract $entityConfig
    */
    public function __construct(
        private EntityConfigContract $entityConfig
    ) { }
    
    /**
     * Define and return the entity's configuration.
     * 
     * @return \Adepta\Proton\Contracts\Entity\EntityConfigContract $entityConfig
    */
    public function getEntityConfig() : EntityConfigContract
    {
        return $this->entityConfig;
    }
}
