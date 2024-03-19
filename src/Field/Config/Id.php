<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Config;

use Adepta\Proton\Field\Config\FieldConfig;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Id as IdField;

final class Id extends FieldConfig
{    
    /**
     * Indication of the field class this config 
     * object corresponds to
     * 
     * @return class-string
     */
    public static function getFieldClass() : string
    {
        return IdField::class;
    }
    
    /**
     * Set initial display contexts for this field
     * type.
     * 
     * @return void
     */
    protected function setInitialDisplayContexts() : void
    {
        $this->displayContexts = collect([
            DisplayContext::VIEW,
            DisplayContext::INDEX,
        ]);
    }
}
