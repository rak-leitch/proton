<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests;

use Adepta\Proton\ProtonServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Adepta\Proton\Tests\Database\Traits\InitialiseDatabaseTrait;
use Adepta\Proton\Tests\EntityDefinitions\Traits\InitialiseEntityConfigTrait;

class TestCase extends BaseTestCase
{
    use InitialiseDatabaseTrait;
    use InitialiseEntityConfigTrait;
    
    /**
     * Basic set up routine for running tests
     *
     * @return void
    */   
    public function setUp(): void
    {   
        parent::setUp();
    }

    /**
     * Get the array of package providers
     *
     * @return array<string>
    */
    protected function getPackageProviders($app) : array
    {
        return [
            ProtonServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @return void
    */
    protected function defineEnvironment($app) : void
    {
        $this->setDbConfig($app);
        $this->setEntityConfig($app);
    }
    
    /**
     * Define database migrations and seeders.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->initialiseTestingDb();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->runSeeders();
    }
    
    /**
     * Get Application base path.
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        //Ensure that the dusk skeleton is used.
        return __DIR__.'/../vendor/orchestra/testbench-dusk/laravel'; 
    }
}
