<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Adepta\Proton\Tests\Models\Project;
use Adepta\Proton\Tests\Models\User;
use Exception;

final class ProjectSeeder extends Seeder
{
    /**
     * @var array<int, array<string, int|string>> $data
     */
    private static array $data = [
        1 => [
            'user_id' => 1,
            'name' => 'Do it yourself',
            'description' => 'All the DIY jobs that need to be done.',
            'priority' => 'normal',
        ],
        2 => [
            'user_id' => 1,
            'name' => 'Fun',
            'description' => 'Non-boring things to do.',
            'priority' => 'high',
        ],
        3 => [
            'user_id' => 2,
            'name' => 'User 2 project',
            'description' => 'User 2 project. Only user 2 may interact.',
            'priority' => 'low',
        ],
    ];
        
    /**
     * Get the project test data
     * 
     * @param int $offset
     * @param string $key
     *
     * @return int|string
    */
    public static function getData(int $offset, string $key) : int|string
    {
        if(empty(self::$data[$offset][$key])) {
            throw new Exception("Could not find test project data key {$key} at offset {$offset}");
        }
        
        return self::$data[$offset][$key];
    }
    
    /**
     * Initialise the Project model table for testing.
     *
     * @return void
    */
    public function run(): void 
    {
        foreach(self::$data as $project) {
            $user = User::findOrFail($project['user_id']);
            Project::factory()->for($user)->create([
                'name' => $project['name'],
                'description' => $project['description'],
                'priority' => $project['priority'],
            ]);
        }
    }
}
