<?php declare(strict_types = 1);

namespace Adepta\Proton\Services;

use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Exceptions\ConfigurationException;

class ConfigStoreService implements ConfigStoreContract
{
    /**
     * @var array<string, string>|null  
    */
    private ?array $definitionClasses;
    
    /**
     * Constructor
     * 
     * Reads the proton config.
    */
    public function __construct()
    {
        /** @phpstan-ignore-next-line  */
        $this->definitionClasses = config('proton.definition_classes');
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
        if($this->definitionClasses === null) {
            throw new ConfigurationException('Failed to find Proton config');
        }
        
        if(empty($this->definitionClasses[$entityCode])) {
            throw new ConfigurationException("No entity definition class found for {$entityCode}");
        }
        
        return $this->definitionClasses[$entityCode];
    }
}
