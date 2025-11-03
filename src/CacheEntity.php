<?php

namespace BitMx\CacheEntities;

use BitMx\CacheEntities\Contracts\CacheableEntity;
use BitMx\CacheEntities\Traits\HasCacheMethods;

/**
 * @template  TReturn
 */
abstract class CacheEntity implements CacheableEntity
{
    /** @use HasCacheMethods<TReturn> */
    use HasCacheMethods;

    protected function hasMemoization(): bool
    {
        return false;
    }

    public function getKey(): string
    {
        return $this->resolveKey();
    }

    abstract protected function resolveKey(): string;

    /**
     * @return TReturn
     */
    abstract protected function resolveValue(): mixed;

    protected function resolveTtl(): \DateInterval|\DateTime|\DateTimeImmutable|int
    {
        return now()->addHour();
    }

    protected function resolveCacheStore(): ?string
    {
        return null;
    }
}
