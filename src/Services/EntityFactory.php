<?php declare(strict_types = 1);

namespace Adepta\Proton\Services;

use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Entity\EntityDefinition;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Exceptions\ConfigurationException;
use \ReflectionClass;

class EntityFactory
{    
    /**
     * Constructor.
     *
     * @param \Adepta\Proton\Contracts\ConfigStoreContract $configStore
    */
    public function __construct(
        private ConfigStoreContract $configStore
    ) { }
    
    /**
     * Create an initialised Entity object based on
     * the entity code.
     * 
     * @param string $entityCode
     *
     * @return \Adepta\Proton\Entity\Entity
    */
    public function create(string $entityCode) : Entity 
    {
        $defintionClass = $this->configStore->getDefinitionClass($entityCode);
        
		if (!class_exists($defintionClass)) {
			throw new ConfigurationException("Entity definition for {$entityCode} not found");
		}
        
        $definitionReflection = new ReflectionClass($defintionClass);
        if(!$definitionReflection->implementsInterface(EntityDefinitionContract::class)) {
            throw new ConfigurationException("Entity definition for {$entityCode} must implement the EntityDefinitionContract");
        }
        
        $entityDefinition = app()->make($defintionClass);
        $entityConfig = $entityDefinition->getEntityConfig();
        $entity = app()->make(Entity::class);
        $entity->initialise($entityConfig);
        return $entity;
    }
}
