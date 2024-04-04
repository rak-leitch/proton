<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Policies;

use Adepta\Proton\Tests\Models\Task;
use Illuminate\Foundation\Auth\User;

class TaskPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param User $user
     * @param Task $task
     * 
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param User $user
     * @param Task $task
     * 
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param User $user
     * @param Task $task
     * 
     * @return bool
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $this->checkOwnership($user, $task);
    }
    
    /**
     * Check the user can perform the action
     * 
     * @param User $user
     * @param Task $task
     * 
     * @return bool
     */
    private function checkOwnership(User $user, Task $task) : bool
    {
        /** @var \Adepta\Proton\Tests\Models\User $user */
        return $user->is_admin || ($task->project && ($task->project->user_id === $user->id));
    }
}
