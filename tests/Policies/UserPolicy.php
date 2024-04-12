<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Adepta\Proton\Tests\Models\User as UserModel;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @param Authenticatable $user
     * 
     * @return bool
     */
    public function viewAny(Authenticatable $user): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param Authenticatable $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function view(Authenticatable $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     * 
     * @param Authenticatable $user
     * 
     * @return bool
     */
    public function create(Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param Authenticatable $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function update(Authenticatable $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param Authenticatable $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function forceDelete(Authenticatable $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }
    
    /**
     * Determine whether the user can add a Project to this user
     * 
     * @param Authenticatable $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function addProject(Authenticatable $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return ((bool)$user->is_admin) || ($userModel->id === $user->id);
    }
}
