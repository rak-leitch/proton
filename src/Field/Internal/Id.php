<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;

use Adepta\Proton\Field\Internal\Field;
use Adepta\Proton\Field\DisplayContext;

final class Id extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return ?string
     */
    public function getFrontendType(DisplayContext $displayContext) : ?string
    {
        return 'text';
    }
    
    /**
     * Indicate this field is a primary key.
     * 
     * @return bool
     */
    public function isPrimaryKey() : bool
    {
        return true;
    }
}
