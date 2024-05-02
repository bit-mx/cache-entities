<?php

use BitMx\CacheEntities\Commands\MakeCacheEntity;

use function Pest\Laravel\artisan;

it('generates a new CacheEntity', function () {
    $name = 'UserCacheEntity';

    artisan(MakeCacheEntity::class, ['name' => $name])
        ->assertSuccessful()
        ->execute();

    $this->assertFileExists(app_path("CacheEntities/{$name}.php"));
});
