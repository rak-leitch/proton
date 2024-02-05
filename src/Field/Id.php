<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;
use Adepta\Proton\Field\Field;
use Adepta\Proton\Field\DisplayContext;
use Illuminate\Support\Collection;

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
     * Get the display contexts for this field.
     * In the case of the ID field, never include this
     * if we are in a mutating context.
     * 
     * @return Collection<int, DisplayContext>
     */
    public function getDisplayContexts() : Collection
    {
        return $this->displayContexts->reject(function($displayContext) {
            return ($displayContext->mutatingContext());
        });
    }
}
