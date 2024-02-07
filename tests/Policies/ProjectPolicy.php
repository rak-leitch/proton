<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Adepta\Proton\Tests\Models\Project;
use Illuminate\Foundation\Auth\User;

class ProjectPolicy
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
        return $user->id === 2 ? false : true;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function view(User $user, Project $project): bool
    {
        return $project->id === 2 ? false : true;
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
        return $user->id === 2 ? false : true;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function update(User $user, Project $project): bool
    {
        return $project->id === 2 ? false : true;
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->id === 2 ? false : true;
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function restore(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return true;
    }
}
