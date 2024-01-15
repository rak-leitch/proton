<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Tests\Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Adepta\Proton\Tests\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     * 
     * @return TaskFactory
     */
    protected static function newFactory() : TaskFactory
    {
        return TaskFactory::new();
    }
    
    /**
     * Get the Project that owns the Task
     * 
     * @return BelongsTo<Project, Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
