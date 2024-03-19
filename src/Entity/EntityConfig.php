<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Adepta\Proton\Exceptions\ConfigurationException;
use Closure;

final class EntityConfig implements EntityConfigContract
{
    private ?string $code;
    private Closure $queryFilter;
    
    /**
     * @var ?class-string<Model>
     */
    private ?string $model;
    
    /**
     * @var Collection<int, FieldContract> $fieldCollection
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
     * Set the entity code that matches a code 
     * defined in the Proton config.
     *
     * @param string $code 
     * 
     * @return self
     */
    public function setCode(string $code) : self
    {
        $this->code = $code;
        return $this;
    }
    
    /**
     * Get the entity code that matches a code 
     * defined in the Proton config. 
     * 
     * @return string
     */
    public function getCode() : string
    {
        if($this->code === null) {
            throw new ConfigurationException('No entity code configured'); 
        }
        
        return $this->code;
    }
    
    /**
     * Set the model associated with this entity
     * configuration.
     *
     * @param class-string<Model> $model 
     * 
     * @return self
     */
    public function setModel(string $model) : self
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
    public function getModel() : string
    {
        if($this->model === null) {
            throw new ConfigurationException('No model class name configured'); 
        }
        
        return $this->model;
    }
    
    /**
     * Add a field to the configuration
     *
     * @param FieldContract $field 
     * 
     * @return self
     */
    public function addField(FieldContract $field) : self
    {
        $this->fieldCollection->push($field);
        return $this;
    }
    
    /**
     * Get the fields collection
     * 
     * @return Collection<int, FieldContract>
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
