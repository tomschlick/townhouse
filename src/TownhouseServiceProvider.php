<?php

namespace TomSchlick\Townhouse;

use Illuminate\Support\ServiceProvider;

class TownhouseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/townhouse.php' => config_path('townhouse.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'townhouse');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/townhouse'),
            ], 'views');
            */
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'townhouse');
    }
}
