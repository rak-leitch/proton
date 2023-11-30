<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests;

use Adepta\Proton\ProtonServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Adepta\Proton\Tests\TestConfig;

class TestCase extends BaseTestCase
{
    /**
     * Basic set up routine for running tests
     *
     * @return void
    */   
    public function setUp(): void
    {
        parent::setUp();
        Config::set('proton', TestConfig::getConfig());
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
