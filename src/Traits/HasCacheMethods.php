<?php

namespace BitMx\CacheEntities\Traits;

use BitMx\CacheEntities\CacheEntity;
use Illuminate\Support\Facades\Cache;

/**
 * @template TReturn
 *
 * @mixin CacheEntity
 */
trait HasCacheMethods
{
    /**
     * Return the value from the cache or resolve it and store it in the cache.
     *
     * @return TReturn
     */
    public function get(): mixed
    {
        return Cache::store($this->resolveCacheStore())
            ->remember($this->resolveKey(), $this->resolveTtl(), fn () => $this->resolveValue());
    }

    /**
     * Forget the cache.
     */
    public function forget(): void
    {
        Cache::store($this->resolveCacheStore())
            ->forget($this->resolveKey());
    }

    public function put(): void
    {
        Cache::store($this->resolveCacheStore())
            ->put($this->resolveKey(), $this->resolveValue(), $this->resolveTtl());
    }

    public function doesNotExist(): bool
    {
        return ! $this->exists();
    }

    public function exists(): bool
    {
        return Cache::store($this->resolveCacheStore())
            ->has($this->resolveKey());
    }
}
