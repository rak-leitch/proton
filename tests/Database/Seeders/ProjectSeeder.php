<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Adepta\Proton\Tests\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Initialise the Project model table for testing.
     *
     * @return void
    */
    public function run(): void
    {
        Project::factory()->create([
            'user_id' => 1,
            'name' => 'Do it yourself',
            'description' => 'All the DIY jobs that need to be done.',
        ]);
        
        Project::factory()->create([
            'user_id' => 1,
            'name' => 'Fun',
            'description' => 'Non-boring things to do.',
        ]);
        
        Project::factory()->create([
            'user_id' => 2,
            'name' => 'User 2 project',
            'description' => 'User 2 project. Only user 2 may interact.',
        ]);
    }
}
