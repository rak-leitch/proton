<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Auth;

use Adepta\Proton\Exceptions\AuthorisationException;
use Illuminate\Foundation\Auth\User;
use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Services\EntityFactory;

final class AuthorisationService
{
    /**
     * Constructor.
     * 
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
    ) { }
    
    /**
     * Check if a user can view an entity type.
     * 
     * @param User $user
     * @param Entity $entity
     * @param bool $throwException
     * 
     * @throws AuthorisationException
     *
     * @return bool
    */
    public function canViewAny(
        ?User $user, 
        Entity $entity, 
        bool $throwException = false
    ) : bool 
    {
        return $this->allowed('viewAny', $user, $entity->getModel(), $throwException);
    }
    
    /**
     * Check if a user can view an entity type.
     * 
     * @param User $user
     * @param Entity $entity
     * @param bool $throwException
     * 
     * @throws AuthorisationException
     *
     * @return bool
    */
    public function canCreate(
        ?User $user, 
        Entity $entity, 
        bool $throwException = false
    ) : bool 
    {
        return $this->allowed('create', $user, $entity->getModel(), $throwException);
    }
    
    /**
     * Check if a user can view an entity instance.
     * 
     * @param User $user
     * @param Model $model
     * @param bool $throwException
     *
     * @return bool
    */
    public function canView(
        ?User $user, 
        Model $model, 
        bool $throwException = false
    ) : bool
    {
        return $this->allowed('view', $user, $model, $throwException);
    }
    
    /**
     * Check if a user can update an entity instance.
     * 
     * @param User $user
     * @param Model $model
     * @param bool $throwException
     *
     * @return bool
    */
    public function canUpdate(
        ?User $user, 
        Model $model, 
        bool $throwException = false
    ) : bool
    {
        return $this->allowed('update', $user, $model, $throwException);
    }
    
    /**
     * Check if a user can delete an entity instance.
     * 
     * @param User $user
     * @param Model $model
     * @param bool $throwException
     *
     * @return bool
    */
    public function canDelete(
        ?User $user, 
        Model $model, 
        bool $throwException = false
    ) : bool 
    {
        return $this->allowed('delete', $user, $model, $throwException);
    }
    
    /**
     * Check if a user can add an entity
     * to its parent (BelongsTo).
     * 
     * @param ?User $user 
     * @param Entity $entity
     * @param FieldContract $field 
     * @param string|int|float|null $fieldValue
     * @param bool $throwException = false
     *
     * @return bool
    */
    public function canAdd(
        ?User $user, 
        Entity $entity,
        FieldContract $field, 
        string|int|float|null $fieldValue,
        bool $throwException = false
    ) : bool 
    {
        $allowed = false;

        if($fieldValue === null) {
            $allowed = true;
        } else {
            $parentEntity = $this->entityFactory->create($field->getRelatedEntityCode());
            $parentModel = $parentEntity->getLoadedModel($fieldValue);
            $policyName = 'add'.$entity->getStudlyCode();
            $allowed = $this->allowed($policyName, $user, $parentModel, $throwException);
        }
        
        return $allowed;
    }
    
    /**
     * Check if a user is allowed to perform an action
     * on a particular entity.
     * 
     * @param string $action
     * @param User $user
     * @param Model|class-string<Model> $model
     * @param bool $throwException
     * 
     * @throws AuthorisationException
     *
     * @return bool
    */
    private function allowed(
        string $action,
        ?User $user, 
        Model|string $model, 
        bool $throwException = false
    ) : bool 
    {
        $allowed = true;
        
        if (!$user || $user->cannot($action, $model)) {
            $allowed = false;
            
            if($throwException) {
                throw new AuthorisationException("You do not have permission to {$action}.");
            }
        }
        
        return $allowed;
    }
}
