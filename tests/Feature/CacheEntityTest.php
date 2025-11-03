<?php

use BitMx\CacheEntities\CacheEntity;
use BitMx\CacheEntities\Traits\HasMemoization;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

it('creates a cache entity', function () {

    /** @var CacheEntity<string> $entity */
    $entity = new class extends CacheEntity
    {
        protected function resolveKey(): string
        {
            return 'key';
        }

        protected function resolveTtl(): DateTime
        {
            return now()->addHour();
        }

        protected function resolveValue(): string
        {
            return 'value';
        }
    };

    $value = $entity->get();

    expect($value)->toBe('value')
        ->and($entity->exists())->toBeTrue()
        ->and($entity->doesNotExist())->toBeFalse()
        ->and($entity->getKey())->toBe('key');

    $entity->forget();

    expect(Cache::has('key'))->toBeFalse();
});

it('remember the value', function () {

    $mockCache = Mockery::mock(Repository::class)
        ->shouldReceive('remember')
        ->once()
        ->with('key', 60, Mockery::type('Closure'))
        ->andReturnUsing(fn ($key, $ttl, $callback) => $callback())
        ->getMock();

    Cache::shouldReceive('driver')
        ->once()
        ->andReturn($mockCache);

    $entity = new class extends CacheEntity
    {
        protected function resolveKey(): string
        {
            return 'key';
        }

        protected function resolveTtl(): int
        {
            return 60;
        }

        protected function resolveValue(): string
        {
            return 'value';
        }
    };

    $value = $entity->get();

    expect($value)->toBe('value');
});

it('forgets the value', function () {
    /** @var CacheEntity<string> $entity */
    $entity = new class extends CacheEntity
    {
        protected function resolveKey(): string
        {
            return 'key';
        }

        protected function resolveTtl(): int
        {
            return 60;
        }

        protected function resolveValue(): string
        {
            return 'value';
        }
    };

    $value = $entity->get();

    expect(Cache::has($entity->getKey()))->toBeTrue();

    $entity->forget();

    expect(Cache::has($entity->getKey()))->toBeFalse();
});

it('has memoization cache value if hasMemoization HasMemoization trait is included', function () {
    /** @var CacheEntity<string> $entity */
    $entityWithMemoization = new class extends CacheEntity
    {
        use HasMemoization;

        protected function resolveKey(): string
        {
            return 'key';
        }

        protected function resolveTtl(): int
        {
            return 60;
        }

        protected function resolveValue(): string
        {
            return 'value';
        }
    };

    $mockMemo = Mockery::mock(Repository::class);
    $mockMemo->shouldReceive('remember')
        ->once()
        ->with('key', 60, Mockery::type('Closure'))
        ->andReturnUsing(fn ($key, $ttl, $callback) => $callback());

    $mockCache = Mockery::mock(Repository::class);
    $mockCache->shouldReceive('memo')
        ->once()
        ->andReturn($mockMemo);

    Cache::shouldReceive('driver')
        ->once()
        ->andReturn($mockCache);

    $value = $entityWithMemoization->get();

    expect($value)->toBe('value');
});

it('does not has memoization cache value if hasMemoization HasMemoization trait is not included', function () {
    /** @var CacheEntity<string> $entity */
    $entityWithoutMemoization = new class extends CacheEntity
    {
        protected function resolveKey(): string
        {
            return 'key';
        }

        protected function resolveTtl(): int
        {
            return 60;
        }

        protected function resolveValue(): string
        {
            return 'value';
        }
    };

    $mockCache = Mockery::mock(Repository::class);

    // Verificar que memo() NO debe ser llamado
    $mockCache->shouldNotReceive('memo');

    // Pero sí debe llamar directamente a remember()
    $mockCache->shouldReceive('remember')
        ->once()
        ->with('key', 60, Mockery::type('Closure'))
        ->andReturnUsing(fn ($key, $ttl, $callback) => $callback());

    Cache::shouldReceive('driver')
        ->once()
        ->andReturn($mockCache);

    $value = $entityWithoutMemoization->get();

    expect($value)->toBe('value');
});
