<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Adepta\Proton\Tests\Models\Project;
use Illuminate\Foundation\Auth\User;

class ProjectPolicy
{
    const CANNOT_INTERACT_PROJECT_ID = 2;
    const NO_PERMISSION_USER_ID = 3;
    
    /**
     * Determine whether the user can view any models.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->id === self::NO_PERMISSION_USER_ID ? false : true;
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
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
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
        return $user->id === self::NO_PERMISSION_USER_ID ? false : true;
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
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
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
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
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
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
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
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
    }
    
    /**
     * Determine whether the user can add a Task to this Project
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    public function addTask(User $user, Project $project): bool
    {
        return $this->checkOwnership($user, $project);
    }
    
    /**
     * Check the user can perform the action
     * 
     * @param User $user
     * @param Project $project
     * 
     * @return bool
     */
    private function checkOwnership(User $user, Project $project) : bool
    {
        /** @var \Adepta\Proton\Tests\Models\User $user */
        return $user->is_admin || ($project->user_id === $user->id);
    }
}
