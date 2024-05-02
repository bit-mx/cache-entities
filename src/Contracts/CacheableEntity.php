<?php

namespace BitMx\CacheEntities\Contracts;

interface CacheableEntity
{
    public function get(): mixed;

    public function getKey(): string;

    public function forget(): void;

    public function exists(): bool;

    public function doesNotExist(): bool;
}
