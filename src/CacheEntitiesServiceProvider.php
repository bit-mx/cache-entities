<?php

namespace BitMx\CacheEntities;

use Illuminate\Support\ServiceProvider;

class CacheEntitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
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
