<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Adepta\Proton\Tests\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Initialise the Task model table for testing.
     *
     * @return void
    */
    public function run(): void
    {
        Task::factory()->create([
            'project_id' => 1,
            'name' => 'Paint the bedroom.',
            'description' => 'Needs to be pink.',
        ]);
        
        Task::factory()->create([
            'project_id' => 1,
            'name' => 'Repair the sink drain.',
            'description' => 'It is leaking everywhere.',
        ]);
        
        Task::factory()->create([
            'project_id' => 2,
            'name' => 'Go to the pub.',
            'description' => 'See if they have 6X on tap.',
        ]);
        
        Task::factory()->create([
            'project_id' => 2,
            'name' => 'Go to the beach.',
            'description' => 'Hope the weather is good.',
        ]);
    }
}
