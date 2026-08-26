<?php

namespace Schemastud\JsonReference;

use Rushing\Popcorn\Registries\RegistryIndex;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-json-reference');
    }

    public function packageRegistered(): void
    {
        // `singletonIf`, not `singleton`: laravel-composition-engine owns the real binding and seeds
        // it with the in-engine `composition` handle, and tower and the host `extend()` that. This is
        // only the floor — without it a host that installs this leaf ALONE hands out a fresh registry
        // per make(), so the instance described into the index below would be one nobody else reads,
        // and the index would lie about a populated registry it had never seen filled.
        $this->app->singletonIf(DereferencerRegistry::class);
    }

    public function packageBooted(): void
    {
        // Declaring and indexing are two acts (registry-kernel ticket 21 D1). This package DECLARES
        // the registry and does not BIND it — laravel-composition-engine owns the singleton, and
        // tower and the host each extend it with their own handles. Describing does not require
        // binding (ticket 04 D10), so the owner of the class describes it, from its own boot, after
        // every provider's register() has run and therefore after every `extend()` that fills it.
        $this->app->make(RegistryIndex::class)->describe(
            $this->app->make(DereferencerRegistry::class),
            by: self::class,
        );
    }
}
