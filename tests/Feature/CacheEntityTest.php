<?php

use BitMx\CacheEntities\CacheEntity;

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
});
