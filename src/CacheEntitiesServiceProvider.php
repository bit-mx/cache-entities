<?php

namespace BitMx\CacheEntities;

use Illuminate\Support\ServiceProvider;

class CacheEntitiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/cache-entities.php' => config_path('cache-entities.php'),
        ], 'config');

    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cache-entities.php', 'cache-entities');

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Commands\MakeCacheEntity::class,
        ]);
    }
}
