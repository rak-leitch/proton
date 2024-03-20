<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Entity\EntityDefinition;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Illuminate\Contracts\Foundation\Application;

final class EntityFactory
{    
    /**
     * Constructor.
     *
     * @param ConfigStoreContract $configStore
    */
    public function __construct(
        private ConfigStoreContract $configStore,
        private Application $app
    ) { }
    
    /**
     * Create an initialised Entity object based on
     * the entity code.
     * 
     * @param string $entityCode
     * 
     * @throws ConfigurationException
     *
     * @return Entity
    */
    public function create(string $entityCode) : Entity 
    {
        $defintionClass = $this->configStore->getDefinitionClass($entityCode);
        $entityDefinition = $this->app->make($defintionClass);
        $entityConfig = $this->app->make(EntityConfigContract::class);
        $entityConfig = $entityDefinition->getEntityConfig($entityConfig);
        $entity = $this->app->make(Entity::class, [
            'entityCode' => $entityCode,
            'entityConfig' => $entityConfig,
        ]);
        
        return $entity;
    }
}
