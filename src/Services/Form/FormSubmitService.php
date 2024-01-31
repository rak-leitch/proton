<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;

class FormSubmitService
{    
    /**
     * Save a model on form submission.
     *
     * @param Entity $entity
     * @param Model $model
     * @param mixed[] $data
     * 
     * @return mixed[]
    */
    public function submit(Entity $entity, Model $model, array $data) : array
    {
        $result = [];
        
        return $result;
    }
}
