<?php declare(strict_types = 1);

namespace Adepta\Proton\Entity;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Field\FieldContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class EntityConfig implements EntityConfigContract
{
    private string $code;
    
    /**
     * @var class-string<Model>
     */
    private string $model;
    
    /**
     * @var Collection<int, FieldContract> $fieldCollection
     */
    protected Collection $fieldCollection;
    
    /**
     * Constructor.
    */
    public function __construct()
    {
        $this->code = '';
        $this->model = Model::class;
        $this->fieldCollection = collect();
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
}
