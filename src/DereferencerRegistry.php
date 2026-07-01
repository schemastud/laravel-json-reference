<?php

declare(strict_types=1);

namespace Rushing\JsonReference;

use Rushing\JsonReference\Exceptions\UnknownDereferencer;

/**
 * Dispatch table for dereferencers, keyed by handle — the read-side mirror of how generate/ground
 * resolve their handlers. A reference carries a handle (read from the unprefixed
 * {@see Vocabulary::Dereference} keyword off the grammar); the registry maps it to the dereferencer
 * that knows that target engine. Dispatch is by handle, never by node type.
 */
final class DereferencerRegistry
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
