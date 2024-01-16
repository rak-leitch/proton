<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Adepta\Proton\Tests\Models\Project;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Project>
     */
    protected $model = Project::class;
    
    /**
     * Define the Project model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(3),
            'priority' => 'normal',
        ];
    }
}
