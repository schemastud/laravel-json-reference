<?php

namespace Schemastud\JsonReference;

use Rushing\Popcorn\Registries\Authorizer;
use Rushing\Popcorn\Registries\BasicRegistry;
use Rushing\Popcorn\Registries\Gated;
use Rushing\Popcorn\Registries\IsRegistry;
use Rushing\Popcorn\Registries\OnDuplicate;
use Rushing\Popcorn\Registries\Registry;
use Rushing\Popcorn\Registries\RegistryKey;
use Schemastud\JsonReference\Exceptions\UnknownDereferencer;

/**
 * Dispatch table for dereferencers, keyed by handle — the read-side mirror of how generate/ground
 * resolve their handlers. A reference carries a handle (read from the unprefixed
 * {@see Vocabulary::Dereference} keyword off the grammar); the registry maps it to the dereferencer
 * that knows that target engine. Dispatch is by handle, never by node type.
 *
 * @implements Registry<Dereferencer>
 */
#[IsRegistry(
    root: 'jsonref.dereferencers',
    entryType: Dereferencer::class,
    onDuplicate: OnDuplicate::Supersede,
    description: 'dereferencers keyed by handle (fragment-corpus, web-page, federated-corpus, conduit) — the read side of a `$ref`, where the handle names the target engine rather than the node shape. The registrants live above this package and in other vendors — six dereferencers across two vendors register here, and the package that BINDS the singleton (laravel-composition-engine) is not this one. Declaring is nonetheless this package\'s, because the class is: describing does not require binding (registry-kernel ticket 04 D10).',
    order: 52,
)]
class DereferencerRegistry implements Gated, Registry
{
    /** @var BasicRegistry<Dereferencer> */
    private BasicRegistry $entries;

    public function __construct()
    {
        $this->entries = BasicRegistry::for($this);
    }

    /**
     * Register a dereferencer under its own {@see Dereferencer::handle()}.
     *
     * WIDENED from the contract rather than shadowing it — contravariance, so every historical
     * one-argument self-keying call `register($dereferencer)` keeps working, and the four providers
     * across three vendors that chain them do not change a line.
     */
    public function register(RegistryKey|string|Dereferencer $key, mixed $entry = null, ?string $by = null, ?string $ability = null): static
    {
        if ($key instanceof Dereferencer) {
            $entry = $key;
            $key = $key->handle();
        }

        $this->entries->register($key, $entry, $by, $ability);

        return $this;
    }

    /**
     * The dereferencer answering for a handle.
     *
     * Port sugar over {@see tryResolve()}, and it keeps throwing {@see UnknownDereferencer} rather
     * than the kernel's `RegistryMiss`: tower's `ResolveHandler` and `DereferenceController` both
     * catch it by name to turn an unknown handle into a 4xx rather than an unhandled 500.
     */
    public function for(string $handle): Dereferencer
    {
        return $this->tryResolve($handle) ?? throw new UnknownDereferencer($handle);
    }

    /**
     * The registered handles, as callers spelled them — {@see keys()} with the declared root stripped
     * back off, because keys go relative in and absolute out (ticket 20 D2).
     *
     * @return string[]
     */
    public function handles(): array
    {
        return $this->entries->relativeKeys();
    }

    public function has(RegistryKey|string $key): bool
    {
        return $this->entries->has($key);
    }

    public function resolve(RegistryKey|string $key): mixed
    {
        return $this->entries->resolve($key);
    }

    public function tryResolve(RegistryKey|string $key): mixed
    {
        return $this->entries->tryResolve($key);
    }

    public function matches(RegistryKey|string $key): array
    {
        return $this->entries->matches($key);
    }

    public function keys(): array
    {
        return $this->entries->keys();
    }

    public function unfiltered(): Registry
    {
        return $this->entries->unfiltered();
    }

    public function authorizeWith(?Authorizer $authorizer): static
    {
        $this->entries->authorizeWith($authorizer);

        return $this;
    }
}
