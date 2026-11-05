<?php

namespace Schemastud\JsonReference;

use Rushing\Popcorn\Registries\ScopesRegistrant;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    use ScopesRegistrant;

    /** Every registry write in register()/boot() that names no `$by` records this package. */
    protected function registrant(): string
    {
        return 'schemastud/laravel-json-reference';
    }

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

    public function packageBooted(): void {}
}
