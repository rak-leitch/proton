<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Adepta\Proton\Tests\Models\Task;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Task>
     */
    protected $model = Task::class;
    
    /**
     * Define the Task model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => 1,
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(3),
        ];
    }
}
