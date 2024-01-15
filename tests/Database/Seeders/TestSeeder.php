<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the seeders needed for testing.
     *
     * @return void
    */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
