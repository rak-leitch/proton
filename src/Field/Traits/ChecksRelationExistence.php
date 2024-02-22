<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Traits;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Adepta\Proton\Exceptions\ConfigurationException;

trait ChecksRelationExistence
{
    /**
     * Get the existence of a relation method 
     * on a model class.
     * 
     * @param Model|class-string $model 
     * @param string $relationMethod
     * 
     * @return void
    */
    public function checkModelRelation(Model|string $model, string $relationMethod) : void
    {        
        $reflection = new ReflectionClass($model);
        
        if(!$reflection->hasMethod($relationMethod)) {
            $error = "Could not find relation method {$relationMethod}.";
            throw new ConfigurationException($error);
        }  
    }
}
