<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;

use Adepta\Proton\Field\Internal\Field;
use Adepta\Proton\Field\DisplayContext;

final class Text extends Field
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
}
