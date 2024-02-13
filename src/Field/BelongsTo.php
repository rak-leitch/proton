<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

final class BelongsTo extends Field
{    
    /**
     * Get the field's frontend display type.
     * 
     * @return string
     */
    public function getFrontendType() : string
    {
        //TODO: This needs to be context specific
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
    
    /**
     * Get the field's name. Guess this from the 
     * entity code provided.
     * 
     * @return string
     */
    public function getFieldName() : string
    {
        return $this->fieldName.'_id';
    }
}
