<?php

namespace BitMx\CacheEntities\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeCacheEntity extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:cache-entity {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new cache entity class';

    protected string $namespace = 'App\CacheEntities';

    #[\Override]
    protected function getStub(): string
    {
        return $this->getStubPath();
    }

    public function getStubPath(): string
    {
        return __DIR__.'/../../stubs/cache-entity.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    #[\Override]
    protected function getDefaultNamespace(mixed $rootNamespace): string
    {
        return $this->namespace;
    }
}
