<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Database\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Adepta\Proton\Tests\Database\Seeders\TestSeeder;

trait InitialiseDatabaseTrait
{
    const SQLITE_TEST_FILE =  __DIR__ . '/../testing_persistent.sqlite';
    
    /**
     * Check the sqlite DB exists and 
     * create it if not.
     *
     * @return void
    */
    public function initialiseTestingDb()
    { 
        if (!file_exists(static::SQLITE_TEST_FILE)) {
            touch(static::SQLITE_TEST_FILE);
        }
    }
    
    /**
     * Set up the test DB config.
     * 
     * @param Application $app
     *
     * @return void
    */
    public function setDbConfig(Application $app)
    {
        $app['config']->set('database.connections.testing_persistent.driver', 'sqlite');
        $app['config']->set('database.connections.testing_persistent.database', static::SQLITE_TEST_FILE);
    }
    
    /**
     * Run the seeders for testing.
     *
     * @return void
    */
    public function runSeeders()
    {
        Artisan::call('db:seed', ['--class' => TestSeeder::class]);
    }
}
