<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;

final class FormSubmitService
{    
    /**
     * Save a model on form submission given
     * previously validated data.
     *
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * @param Model $model
     * @param mixed[] $data
     * 
     * @return void
    */
    public function submit(
        DisplayContext $displayContext, 
        Entity $entity, 
        Model $model, 
        array $data
    ) : void
    {       
        $expectedFields = $entity->getFields($displayContext);
        
        foreach($expectedFields as $field) {
            $fieldName = $field->getFieldName();
            $model->{$fieldName} = $data[$fieldName];
        }
        
        $model->save();
    }
}
