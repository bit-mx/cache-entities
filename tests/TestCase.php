<?php

declare(strict_types=1);

namespace BitMx\CacheEntities\Tests;

use BitMx\CacheEntities\CacheEntitiesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            CacheEntitiesServiceProvider::class,
        ];
    }
}
