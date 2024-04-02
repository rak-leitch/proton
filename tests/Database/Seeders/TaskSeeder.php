<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Adepta\Proton\Tests\Models\Task;
use Exception;

class TaskSeeder extends Seeder
{
    /**
     * Get the task test data
     * 
     * @param int $offset
     * @param string $key
     *
     * @return int|string
    */
    public static function getData(int $offset, string $key) : int|string
    {
        $data = [
            1 => [
                'project_id' => 1,
                'name' => 'Paint the bedroom.',
                'description' => 'Needs to be pink.',
            ],
            2 => [
                'project_id' => 1,
                'name' => 'Repair the sink drain.',
                'description' => 'It is leaking everywhere.',
            ],
            3 => [
                'project_id' => 2,
                'name' => 'Go to the pub.',
                'description' => 'See if they have 6X on tap.',
            ],
            4 => [
                'project_id' => 2,
                'name' => 'Go to the beach.',
                'description' => 'Hope the weather is good.',
            ],
            5 => [
                'project_id' => 3,
                'name' => 'Top secret task',
                'description' => 'Only user 2 is able to interact.',
            ],
        ];
        
        if(empty($data[$offset][$key])) {
            throw new Exception("Could not find test task data key {$key} at offset {$offset}");
        }
        
        return $data[$offset][$key];
    }
    /**
     * Initialise the Task model table for testing.
     *
     * @return void
    */
    public function run(): void
    {
        for($count = 1; $count <= 5; $count++) {
            Task::factory()->create([
                'project_id' => self::getData($count, 'project_id'),
                'name' => self::getData($count, 'name'),
                'description' => self::getData($count, 'description'),
            ]);
        }
    }
}
