<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;
use Adepta\Proton\Field\Field;

final class Id extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @return string
     */
    public function getFrontendType() : string
    {
        //TODO: enum?
        return 'text';
    }
}
