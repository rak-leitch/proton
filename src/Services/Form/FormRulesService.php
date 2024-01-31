<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;

class FormRulesService
{    
    /**
     * Get the rules for a form submit based on
     * the entity's fields.
     *
     * @param Entity $entity
     * 
     * @return array<string, string>
    */
    public function getRules(Entity $entity) : array
    {
        $rules = [];
        
        $validationFields = $entity->getFields();
        
        foreach($validationFields as $field) {
            $rules[$field->getFieldName()] = $field->getValidation();    
        }
        
        return $rules;
    }
}
