<?php declare(strict_types = 1);

namespace Adepta\Proton\Services;

use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Entity\EntityDefinition;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use \ReflectionClass;

final class EntityFactory
{    
    /**
     * Constructor.
     *
     * @param ConfigStoreContract $configStore
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
     * @throws ConfigurationException
     *
     * @return Entity
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
        $entityConfig = app()->make(EntityConfigContract::class);
        $entityConfig = $entityDefinition->getEntityConfig($entityConfig);
        $entity = app()->make(Entity::class, [
            'entityCode' => $entityCode,
            'entityConfig' => $entityConfig,
        ]);
        
        return $entity;
    }
}
