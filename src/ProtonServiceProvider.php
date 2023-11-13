<?php declare(strict_types = 1);

namespace Adepta\Proton;

use Illuminate\Support\ServiceProvider;

class ProtonServiceProvider extends ServiceProvider
{
    /**
     * Regsiter services etc.
     *
     * @return void
    */
    public function register(): void
    {

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
