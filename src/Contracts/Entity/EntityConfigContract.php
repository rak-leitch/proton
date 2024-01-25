<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Entity;

use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Collection;

interface EntityConfigContract
{
    /**
     * Set the entity code that matches a code 
     * defined in the Proton config.
     *
     * @param string $code 
     * 
     * @return self
     */
    public function setCode(string $code) : self;
    
    /**
     * Get the entity code that matches a code 
     * defined in the Proton config. 
     * 
     * @return string
     */
    public function getCode() : string;
    
    /**
     * Set the model associated with this entity
     * configuration.
     *
     * @param string $model 
     * 
     * @return self
     */
    public function setModel(string $model) : self;
    
    /**
     * Get the model associated with this entity
     * configuration. 
     * 
     * @return string
     */
    public function getModel() : string;
    
    /**
     * Add a field to the configuration
     *
     * @param FieldContract $field 
     * 
     * @return self
     */
    public function addField(FieldContract $field) : self;
    
    /**
     * Get the fields collection
     * 
     * @return Collection<int, FieldContract>
     */
    public function getFields() : Collection;
}
