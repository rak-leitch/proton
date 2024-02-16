<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

use Adepta\Proton\Field\DisplayContext;

final class Id extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @return string
     */
    public function getFrontendType(DisplayContext $displayContext) : string
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
