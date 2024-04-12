<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Adepta\Proton\Field\Internal\BelongsTo;

final class FormSubmitService
{    
    /**
     * Constructor.
     * 
     * @param AuthorisationService $authService
    */
    public function __construct(
        private AuthorisationService $authService,
    ) { }
    
    /**
     * Save a model on form submission given
     * previously validated data.
     *
     * @param ?Authenticatable $user
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * @param Model $model
     * @param array<string, string|int|float|bool|null> $data
     * 
     * @return void
    */
    public function submit(
        ?Authenticatable $user,
        DisplayContext $displayContext, 
        Entity $entity, 
        Model $model, 
        array $data
    ) : void
    {       
        $expectedFields = $entity->getFields(
            displayContext: $displayContext
        );
        
        foreach($expectedFields as $field) {
            $fieldName = $field->getFieldName();
            $fieldValue = $data[$fieldName];
            
            //Check they are allowed to add this entity to a parent
            if($field->getClass() === BelongsTo::class) { 
                $this->authService->canAdd($user, $entity, $field, $fieldValue, true);
            }
            
            $model->{$fieldName} = $fieldValue;
        }
        
        $model->save();
    }
}
