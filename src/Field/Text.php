<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

final class Text extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @return string
     */
    public function getFrontendType() : string
    {
        return 'text';
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
            DisplayContext::CREATE,
            DisplayContext::UPDATE,
            DisplayContext::VIEW,
            DisplayContext::INDEX,
        ]);
    }
}
