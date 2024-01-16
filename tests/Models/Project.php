<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Tests\Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Adepta\Proton\Tests\Models\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Adepta\Proton\Tests\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Project extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     * 
     * @return ProjectFactory
     */
    protected static function newFactory() : ProjectFactory
    {
        return ProjectFactory::new();
    }
    
    /**
     * Get the Tasks belonging to a Project.
     * 
     * @return HasMany<Task>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    /**
     * Get the User that owns the Project
     * 
     * @return BelongsTo<User, Project>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
