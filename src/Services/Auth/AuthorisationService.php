<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Auth;

use Adepta\Proton\Exceptions\AuthorisationException;

use Illuminate\Foundation\Auth\User;
use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;

class AuthorisationService
{
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
        $canViewAny = true;
        $modelClass = $entity->getModel();
        
        if (!$user || $user->cannot('viewAny', $modelClass)) {
            $canViewAny = false;
            
            if($throwException) {
                throw new AuthorisationException('You do not have permission to view this entity type.');
            }
        }
        
        return $canViewAny;
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
     * Check if a user is allowed to perform an action
     * on a particular entity.
     * 
     * @param string $action
     * @param User $user
     * @param Model $model
     * @param bool $throwException
     * 
     * @throws AuthorisationException
     *
     * @return bool
    */
    private function allowed(
        string $action,
        ?User $user, 
        Model $model, 
        bool $throwException = false
    ) : bool 
    {
        $allowed = true;
        
        if (!$user || $user->cannot($action, $model)) {
            $allowed = false;
            
            if($throwException) {
                throw new AuthorisationException("You do not have permission to {$action} this entity.");
            }
        }
        
        return $allowed;
    }
         
}
