<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Adepta\Proton\Field\DisplayContext;
use Illuminate\Validation\ValidationRuleParser;

class FormValidationService
{    
    /**
     * Get the rules for a form submit based on
     * the entity's fields. Given a display context, we
     * can ensure that all expected fields are present.
     *
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * 
     * @return array<string, string>
    */
    public function getRules(DisplayContext $displayContext, Entity $entity) : array
    {
        $rules = [];
        
        $validationFields = $entity->getFields($displayContext);
        
        foreach($validationFields as $field) {
            $validationString = $field->getValidation();
            $validationString = empty($validationString) ? 'present' : "present|{$validationString}";
            $rules[$field->getFieldName()] = $validationString;    
        }
        
        return $rules;
    }
}
