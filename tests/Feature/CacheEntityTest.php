<?php

use BitMx\CacheEntities\CacheEntity;
use Illuminate\Support\Facades\Cache;

it('creates a cache entity', function () {

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
    Cache::spy();
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

    Cache::shouldHaveReceived('remember');
});

it('forgets the value', function () {
    Cache::spy();

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

    $entity->forget();

    Cache::shouldHaveReceived('forget');
});
