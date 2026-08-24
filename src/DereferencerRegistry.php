<?php

namespace Schemastud\JsonReference;

use Rushing\Popcorn\Registries\IsRegistry;
use Rushing\Popcorn\Registries\OnDuplicate;
use Rushing\Popcorn\Registries\RegistryArity;
use Schemastud\JsonReference\Exceptions\UnknownDereferencer;

/**
 * Dispatch table for dereferencers, keyed by handle — the read-side mirror of how generate/ground
 * resolve their handlers. A reference carries a handle (read from the unprefixed
 * {@see Vocabulary::Dereference} keyword off the grammar); the registry maps it to the dereferencer
 * that knows that target engine. Dispatch is by handle, never by node type.
 */
#[IsRegistry(
    root: 'jsonref.dereferencers',
    of: 'dereferencers keyed by handle (fragment-corpus, web-page, federated-corpus, conduit) — the '
        .'read side of a `$ref`, where the handle names the target engine rather than the node shape',
    arity: RegistryArity::PickOne,
    entryType: Dereferencer::class,
    onDuplicate: OnDuplicate::Supersede,
    note: 'The registrants live above this package and in other vendors — six dereferencers across two '
        .'vendors register here, and the package that BINDS the singleton (laravel-composition-engine) '
        .'is not this one. Declaring is nonetheless this package\'s, because the class is: describing '
        .'does not require binding (registry-kernel ticket 04 D10).',
    order: 52,
)]
class DereferencerRegistry
{
    /** @var array<string, Dereferencer> */
    private array $byHandle = [];

    public function register(Dereferencer $dereferencer): self
    {
        $this->byHandle[$dereferencer->handle()] = $dereferencer;

        return $this;
    }

    public function for(string $handle): Dereferencer
    {
        return $this->byHandle[$handle] ?? throw new UnknownDereferencer($handle);
    }

    public function has(string $handle): bool
    {
        return isset($this->byHandle[$handle]);
    }
}
