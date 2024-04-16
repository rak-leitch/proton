<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Adepta\Proton\Tests\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Adepta\Proton\Tests\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Adepta\Proton\Tests\Models\User
 * 
 * @property string $name
 * @property bool $is_admin
 */
class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Create a new factory instance for the model.
     * 
     * @return UserFactory
     */
    protected static function newFactory() : UserFactory
    {
        return UserFactory::new();
    }
    
    /**
     * Get the Projects belonging to a User.
     * 
     * @return HasMany<Project>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
