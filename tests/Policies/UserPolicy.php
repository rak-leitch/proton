<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Illuminate\Foundation\Auth\User;
use Adepta\Proton\Tests\Models\User as UserModel;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param User $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function view(User $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param User $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function update(User $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param User $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function forceDelete(User $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin;
    }
    
    /**
     * Determine whether the user can add a Project to this user
     * 
     * @param User $user
     * @param UserModel $userModel
     * 
     * @return bool
     */
    public function addProject(User $user, UserModel $userModel): bool
    {
        /** @var UserModel $user */
        return (bool)$user->is_admin || ($userModel->id === $user->id);
    }
}
