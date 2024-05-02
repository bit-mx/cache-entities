<?php

namespace BitMx\CacheEntities\Traits;

trait HasCacheStore
{
    protected function resolveCacheStore(): string
    {
        return config('cache-entities.driver', 'file');
    }
}
