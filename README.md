# Cache Entities

Manage your cache easily with Cache Entities.

Table of Contents
=================

* [Introduction](#introduction)
* [Installation](#installation)
* [Compatibility](#compatibility)
* [Getting Started](#getting-started)
  * [Create a Cache Entity class](#create-a-cache-entity-class)
  * [Driver](#driver)
  * [Get the cached value](#get-the-cached-value)
* [Helper methods](#helper-methods)
  * [exists](#exists)
  * [doesNotExist](#doesnotexist)
  * [forget](#forget)
* [Memoization](#memoization)



## Introduction

Cache Entities is a package that allows you to manage your cache using a simple and clean API.

## Installation

You can install the package via composer:

```bash
composer require bit-mx/cache-entities
```

## Compatibility

This package is compatible with Laravel 10.x and above.

Due laravel 11 requires php 8.2, this package is compatible with php 8.2 and above.

## Getting Started

### Create a Cache Entity class

To create a Cache Entity, you need to extend the CacheEntity class and implement the resolveKey, resolveTtl, and
resolveValue methods.

```php
namespace App\CacheEntities;

use BitMx\CacheEntities\CacheEntity;
use App\Models\User;
use Carbon\CarbonInterval;

class CurrentUserCache extends CacheEntity
{
    public function __construct(
        protected int $id,
    )
    {
    }

    protected function resolveKey(): string
    {
        return sprintf('current_user:%s', $this->id);
    }

    protected function resolveTtl(): CarbonInterval
    {
        return CarbonInterval::days(12);
    }

    protected function resolveValue(): mixed
    {
        return User::find($this->id);
    }
}
```

You can use the artisan command to create a new Cache Entity:

```bash
php artisan make:cache-entity CurrentUserCache
```

This command will create a new Cache Entity in the `app/cacheEntities` directory.

### Driver

You can set the driver name overriding the resolveCacheStore method.

```php
namespace App\CacheEntities;

use BitMx\CacheEntities\CacheEntity;
use App\Models\User;
use Carbon\CarbonInterval;

class CurrentUserCache extends CacheEntity
{
    
    protected function resolveCacheStore(): string
    {
        return 'redis';
    }
}
```

### Get the cached value 

To get the cached value, you can use the get method.

```php
use App\CacheEntities\CurrentUserCache;

$cacheEntity = new CurrentUserCache(1);

$user = $cacheEntity->get();
```

## Helper methods

You can use the following helper methods to work with the cache:

### exists

The exists method returns true if the cache key exists, and false otherwise.

```php
use App\CacheEntities\CurrentUserCache;

$cacheEntity = new CurrentUserCache(1);

$user = $cacheEntity->get();

if ($cacheEntity->exists()) {
    // The cache key exists
} else {
    // The cache key does not exist
}
```

### doesNotExist

The doesNotExist method returns true if the cache key does not exist, and false otherwise.

```php
use App\CacheEntities\CurrentUserCache;

$cacheEntity = new CurrentUserCache(1);

$user = $cacheEntity->get();

if ($cacheEntity->doesNotExist()) {
    // The cache key does not exist
} else {
    // The cache key exists
}
```

### forget

The forget method removes the cache key.

```php
use App\CacheEntities\CurrentUserCache;

$cacheEntity = new CurrentUserCache(1);

$user = $cacheEntity->get();

$cacheEntity->forget();
```

## Memoization

By default, Cache Entities do not use memoization. However, you can enable it by using the `HasMemoization` trait in your Cache Entity class.

When memoization is enabled, the cache entity will use Laravel's `memo()` method to store the cached value in memory during the request lifecycle. This prevents redundant cache lookups for the same key within a single request, improving performance.

### Enabling Memoization

To enable memoization, simply add the `HasMemoization` trait to your Cache Entity class:

```php
namespace App\CacheEntities;

use BitMx\CacheEntities\CacheEntity;
use BitMx\CacheEntities\Traits\HasMemoization;
use App\Models\User;
use Carbon\CarbonInterval;

class CurrentUserCache extends CacheEntity
{
    use HasMemoization;

    public function __construct(
        protected int $id,
    )
    {
    }

    protected function resolveKey(): string
    {
        return sprintf('current_user:%s', $this->id);
    }

    protected function resolveTtl(): CarbonInterval
    {
        return CarbonInterval::days(12);
    }

    protected function resolveValue(): mixed
    {
        return User::find($this->id);
    }
}
```

With memoization enabled:
- The first call to `get()` will fetch the value from cache or execute `resolveValue()`
- Subsequent calls to `get()` within the same request will return the memoized value from memory
- This avoids redundant cache lookups, improving performance when accessing the same cache entity multiple times

### Without Memoization

If you don't use the `HasMemoization` trait, each call to `get()` will perform a cache lookup, even within the same request.
