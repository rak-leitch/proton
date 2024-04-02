<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

enum DisplayContext
{
    case CREATE;
    case UPDATE;
    case DISPLAY;
    case INDEX;
    
    /**
     * Indication of whether a context
     * can mutate data.
     * 
     * @return bool
     */
    public function mutatingContext(): bool
    {
        return match($this) {
            self::CREATE => true,   
            self::UPDATE => true,   
            self::DISPLAY => false,
            self::INDEX => false,   
        };
    }
}
