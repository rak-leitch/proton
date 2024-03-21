<?php declare(strict_types = 1);

namespace Adepta\Proton\Field\Internal;

use Adepta\Proton\Field\Internal\Field;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\FrontendType;

final class Text extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @param DisplayContext $displayContext
     * 
     * @return FrontendType
     */
    public function getFrontendType(DisplayContext $displayContext) : FrontendType
    {
        return FrontendType::TEXT;
    }
}
