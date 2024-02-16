<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Entity;

use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Closure;

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
     * @param class-string<Model> $model 
     * 
     * @return self
     */
    public function setModel(string $model) : self;
    
    /**
     * Get the model associated with this entity
     * configuration. 
     * 
     * @return class-string<Model>
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
    
    /**
     * Set the query filter function
     * 
     * @param Closure $filter
     * 
     * @return void
     */
    public function setQueryFilter(Closure $filter) : void;
    
    /**
     * Get the query filter function
     * 
     * @return Closure
     */
    public function getQueryFilter() : Closure;
}
