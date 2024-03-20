<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Foundation\Auth\User;
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
     * @param ?User $user
     * @param DisplayContext $displayContext
     * @param Entity $entity
     * @param Model $model
     * @param array<string, string|int|float|null> $data
     * 
     * @return void
    */
    public function submit(
        ?User $user,
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
            
            $model->{$fieldName} = $data[$fieldName];
        }
        
        $model->save();
    }
}
