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
    public function run()
    {
        User::factory()->create();
    }
}
