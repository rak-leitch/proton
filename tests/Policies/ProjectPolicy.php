<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Adepta\Proton\Tests\Models\Project;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProjectPolicy
{
    const CANNOT_INTERACT_PROJECT_ID = 2;
    const NO_PERMISSION_USER_ID = 3;
    
    /**
     * Determine whether the user can view any models.
     * 
     * @param Authenticatable $user
     * 
     * @return bool
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->id === self::NO_PERMISSION_USER_ID ? false : true;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param Authenticatable $user
     * @param Project $project
     * 
     * @return bool
     */
    public function view(Authenticatable $user, Project $project): bool
    {   
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
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
        return $user->id === self::NO_PERMISSION_USER_ID ? false : true;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param Authenticatable $user
     * @param Project $project
     * 
     * @return bool
     */
    public function update(Authenticatable $user, Project $project): bool
    {
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param Authenticatable $user
     * @param Project $project
     * 
     * @return bool
     */
    public function forceDelete(Authenticatable $user, Project $project): bool
    {
        return $project->id === self::CANNOT_INTERACT_PROJECT_ID ? false : $this->checkOwnership($user, $project);
    }
    
    /**
     * Determine whether the user can add a Task to this Project
     * 
     * @param Authenticatable $user
     * @param Project $project
     * 
     * @return bool
     */
    public function addTask(Authenticatable $user, Project $project): bool
    {
        return $this->checkOwnership($user, $project);
    }
    
    /**
     * Check the user can perform the action
     * 
     * @param Authenticatable $user
     * @param Project $project
     * 
     * @return bool
     */
    private function checkOwnership(Authenticatable $user, Project $project) : bool
    {
        /** @var \Adepta\Proton\Tests\Models\User $user */
        return $user->is_admin || ($project->user_id === $user->id);
    }
}
