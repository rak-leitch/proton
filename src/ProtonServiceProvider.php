<?php declare(strict_types = 1);

namespace Adepta\Proton;

use Illuminate\Support\ServiceProvider;
use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Services\ConfigStoreService;
use Adepta\Proton\Entity\EntityConfig;

class ProtonServiceProvider extends ServiceProvider
{
    /**
     * Regsiter services etc.
     *
     * @return void
    */
    public function register(): void
    {
         $this->app->singleton(ConfigStoreContract::class, ConfigStoreService::class);
         $this->app->bind(EntityConfigContract::class, EntityConfig::class);
    }

    /**
     * Bootstrap routes, views etc
     *
     * @return void
    */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'proton');
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public/adepta/proton/assets/' => public_path('adepta/proton/assets'),
            ], 'assets');
        }
    }
}
