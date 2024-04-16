<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Adepta\Proton\Tests\Models\Task;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TaskPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param Authenticatable $user
     * @param Task $task
     * 
     * @return bool
     */
    public function view(Authenticatable $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param Authenticatable $user
     * @param Task $task
     * 
     * @return bool
     */
    public function update(Authenticatable $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param Authenticatable $user
     * @param Task $task
     * 
     * @return bool
     */
    public function forceDelete(Authenticatable $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
    }
    
    /**
     * Check the user can perform the action
     * 
     * @param Authenticatable $user
     * @param Task $task
     * 
     * @return bool
     */
    private function checkOwnership(Authenticatable $user, Task $task) : bool
    {
        /** @var \Adepta\Proton\Tests\Models\User $user */
        return $user->is_admin || ($task->project && ($task->project->user_id === $user->id));
    }
}
