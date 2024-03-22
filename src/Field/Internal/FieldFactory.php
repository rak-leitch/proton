<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;

use Adepta\Proton\Contracts\Field\FieldConfigContract;
use Adepta\Proton\Field\Internal\Field;
use Illuminate\Contracts\Foundation\Application;

final class FieldFactory
{    
    /**
     * Constructor.
     *
     * @param Application $app
    */
    public function __construct(
        private Application $app
    ) { }
    
    /**
     * Create an internal Field from a FieldConfig object
     * 
     * @param FieldConfigContract $fieldConfig
     * 
     * @return Field
     */
    public function create(FieldConfigContract $fieldConfig) : Field
    {
        $fieldClass = $fieldConfig->getInternalFieldClass();
        
        return $this->app->make($fieldClass, [
            'fieldConfig' => $fieldConfig,
        ]);
    }
}
