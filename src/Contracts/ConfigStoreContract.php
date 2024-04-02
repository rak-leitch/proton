<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts;

interface ConfigStoreContract
{
    /**
     * Returns the corresponding entity definition class for
     * an entity code.
     *
     * @param string $entityCode
     *
     * @return string
    */
    public function getDefinitionClass(string $entityCode) : string;
    
    /**
     * Get all entity codes
     *
     * @return array<int, string>
    */
    public function getAllEntityCodes() : array;
}
