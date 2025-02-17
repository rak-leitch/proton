<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests;

use Adepta\Proton\ProtonServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as BaseTestCase;
use Adepta\Proton\Tests\Database\Traits\InitialiseDatabaseTrait;
use Adepta\Proton\Tests\EntityDefinitions\Traits\InitialiseEntityConfigTrait;
use Laravel\Dusk\Browser;

class BrowserTestCase extends BaseTestCase
{
    use InitialiseDatabaseTrait;
    use InitialiseEntityConfigTrait;

    protected static $baseServeHost = '127.0.0.1';
    protected static $baseServePort = 9001;
    
    /**
     * Basic set up routine for running Browser tests
     *
     * @return void
    */
    public function setUp(): void
    {        
        parent::setUp();
        
        //Custom Browser command to clear a Vue input element
        //See https://github.com/laravel/dusk/issues/818
        Browser::macro('clearVue', function ($selector) {
            /** @var Browser $this */
            $element = $this->resolver->format($selector);
            $this->script("document.querySelector('$element').value = ''");
            $this->script("document.querySelector('$element').dispatchEvent(new Event('input'))");

            return $this;
        });
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
}
