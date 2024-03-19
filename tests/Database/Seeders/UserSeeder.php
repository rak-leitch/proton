<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Adepta\Proton\Tests\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Initialise the User table for testing.
     *
     * @return void
    */
    public function run(): void
    {
        for($userCount = 0; $userCount < 3; $userCount++) {
            User::factory()->create();
        }
    }
}
