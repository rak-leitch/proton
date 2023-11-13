<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests;

use Adepta\Proton\ProtonServiceProvider;
use Orchestra\Testbench\Dusk\TestCase as BaseTestCase;

class BrowserTestCase extends \Orchestra\Testbench\Dusk\TestCase
{
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
     * Define environment setup. Currently this
     * is done in phpunit.xml.
     *
     * @return void
    */
    protected function getEnvironmentSetUp($app) : void
    {

    }
}
