<?php

namespace Tests\PHPStan;

use BitMx\CacheEntities\CacheEntity;
use BitMx\CacheEntities\Traits\HasMemoization;
use Carbon\CarbonInterval;

/**
 * This class exists solely to satisfy PHPStan's trait usage analysis.
 * It is never instantiated or used in production code.
 *
 * @internal
 *
 * @extends CacheEntity<null>
 */
final class HasMemoizationStub extends CacheEntity
{
    use HasMemoization;

    protected function resolveKey(): string
    {
        return 'stub';
    }

    protected function resolveTtl(): CarbonInterval
    {
        return CarbonInterval::hour();
    }

    protected function resolveValue(): mixed
    {
        return null;
    }
}
