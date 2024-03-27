<?php declare(strict_types = 1);

namespace Adepta\Proton\Contracts\Entity;

use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Closure;

interface EntityConfigContract
{    
    /**
     * Set the model associated with this entity
     * configuration.
     *
     * @param class-string<Model> $model 
     * 
     * @return self
     */
    public function setModelClass(string $model) : self;
    
    /**
     * Get the model associated with this entity
     * configuration. 
     * 
     * @return class-string<Model>
     */
    public function getModelClass() : string;
    
    /**
     * Add a field to the configuration
     *
     * @param FieldConfigContract $field 
     * 
     * @return self
     */
    public function addField(FieldConfigContract $field) : self;
    
    /**
     * Get the fields collection
     * 
     * @return Collection<int, FieldConfigContract>
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
