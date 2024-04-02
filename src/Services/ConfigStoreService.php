<?php declare(strict_types = 1);

namespace Adepta\Proton\Services;

use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Exceptions\ConfigurationException;
use Adepta\Proton\Exceptions\EntityNotFoundException;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use ReflectionClass;

final class ConfigStoreService implements ConfigStoreContract
{
    /**
     * @var array<string, class-string> 
    */
    private array $definitionClasses;
    
    /**
     * Constructor
     * 
     * Reads the proton config.
    */
    public function __construct()
    {
        $this->readDefinitionConfig();
    }

    /**
     * Gets the entity definition class based on a 
     * supplied entity code.
     * 
     * @param string $entityCode
     * 
     * @throws ConfigurationException
     *
     * @return string
    */
    public function getDefinitionClass(string $entityCode) : string
    {
        if(empty($this->definitionClasses[$entityCode])) {
            throw new EntityNotFoundException("No entity definition class found for {$entityCode}");
        }
        
        $defintionClass = $this->definitionClasses[$entityCode];
        
        $definitionReflection = new ReflectionClass($defintionClass);
        if(!$definitionReflection->implementsInterface(EntityDefinitionContract::class)) {
            throw new ConfigurationException("Entity definition for {$entityCode} must implement the EntityDefinitionContract");
        }
        
        return $defintionClass;
    }
    
    /**
     * Get all entity codes
     *
     * @return array<int, string>
    */
    public function getAllEntityCodes() : array
    {
        return array_keys($this->definitionClasses);
    }
    
    /**
     * Read and do some basic verification on the 
     * entity definition config.
     * 
     * @throws ConfigurationException
     *
     * @return void
    */
    private function readDefinitionConfig()
    {
        $definitionConfig = config('proton.definition_classes');
        
        if(is_array($definitionConfig)) {
            foreach($definitionConfig as $code => $class) {
                if(is_string($code)) {
                    if(class_exists($class)) {
                        $this->definitionClasses[$code] = $class;
                    } else {
                        throw new ConfigurationException("Entity definition class {$class} does not exist.");
                    }
                } else {
                    throw new ConfigurationException("Entity definition code {$code} must be a string.");
                }
            } 
        } else {
            throw new ConfigurationException('Entity definition config must be an array');
        }
    }
}
