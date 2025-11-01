<?php

namespace BitMx\CacheEntities\Traits;

use BitMx\CacheEntities\CacheEntity;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * @template TReturn
 *
 * @mixin CacheEntity<TReturn>
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
        $driver = $this->resolveDriver();

        if ($this->hasMemoization()) {
            return $this->rememberValue($driver->memo());
        }

        return $this->rememberValue($driver);
    }

    /**
     * Forget the cache.
     */
    public function forget(): void
    {
        $this->resolveDriver()->forget($this->resolveKey());
    }

    public function put(): void
    {
        $this->resolveDriver()->put($this->resolveKey(), $this->resolveValue(), $this->resolveTtl());
    }

    public function doesNotExist(): bool
    {
        return ! $this->exists();
    }

    public function getKey(): string
    {
        return $this->resolveKey();

    }

    public function exists(): bool
    {
        return $this->resolveDriver()->has($this->resolveKey());
    }

    protected function resolveDriver(): Repository
    {
        $storeName = $this->resolveCacheStore();

        // Si no se especifica store, usar el driver por defecto directamente
        if ($storeName === null) {
            return Cache::driver();
        }

        return Cache::store($storeName);
    }

    /**
     * Return the value from the cache or resolve it and store it in the cache.
     *
     * @return TReturn
     */
    private function rememberValue(Repository $cacheDriver): mixed
    {
        return $cacheDriver->remember(
            $this->resolveKey(),
            $this->resolveTtl(),
            fn () => $this->resolveValue(),
        );
    }
}
