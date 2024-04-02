<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Adepta\Proton\Exceptions\ConfigurationException;
use Closure;

final class EntityConfig implements EntityConfigContract
{
    private Closure $queryFilter;
    
    /**
     * @var ?class-string<Model>
     */
    private ?string $model;
    
    /**
     * @var Collection<int, FieldConfigContract> $fieldCollection
     */
    private Collection $fieldCollection;
    
    /**
     * Constructor.
    */
    public function __construct()
    {
        $this->fieldCollection = collect();
        $this->queryFilter = function(Builder $query) { };
    }
    
    /**
     * Set the model associated with this entity
     * configuration.
     *
     * @param class-string<Model> $model 
     * 
     * @return self
     */
    public function setModelClass(string $model) : self
    {
        $this->model = $model;
        return $this;
    }
    
    /**
     * Get the model associated with this entity
     * configuration.
     * 
     * @return class-string<Model>
     */
    public function getModelClass() : string
    {
        if($this->model === null) {
            throw new ConfigurationException('No model class name configured'); 
        }
        
        return $this->model;
    }
    
    /**
     * Add a field to the configuration
     *
     * @param FieldConfigContract $field 
     * 
     * @return self
     */
    public function addField(FieldConfigContract $field) : self
    {
        $this->fieldCollection->push($field);
        return $this;
    }
    
    /**
     * Get the fields collection
     * 
     * @return Collection<int, FieldConfigContract>
     */
    public function getFields() : Collection
    {
        return $this->fieldCollection;
    }
    
    /**
     * Set the query filter function
     * 
     * @param Closure $filter
     * 
     * @return void
     */
    public function setQueryFilter(Closure $filter) : void
    {
        $this->queryFilter = $filter;
    }
    
    /**
     * Get the query filter function
     * 
     * @return Closure
     */
    public function getQueryFilter() : Closure
    {
        return $this->queryFilter;
    }
}
