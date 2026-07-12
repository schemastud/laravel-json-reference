<?php

namespace Schemastud\JsonReference;

use Illuminate\Support\Str;
use Stringable;

/**
 * An opaque, content- and position-independent node identity — the "birth certificate" minted
 * once at a node's creation and carried immutably thereafter. It surfaces at the instance level
 * as {@see Vocabulary::Id} (`@id`, JSON-LD instance identity).
 *
 * Identity is orthogonal to a node's position and content: reordering or editing a node never
 * changes its NodeId, and two nodes with identical content at different positions still get
 * distinct ids. Position lives on a separate axis (the kernel's `nodePath`).
 */
class NodeId implements Stringable
{
    public const Prefix = 'nod_';

    private function __construct(public string $value) {}

    /**
     * Mint a fresh, globally-unique identity. Random (not a content hash), so equal content at
     * different positions yields different ids.
     */
    public static function mint(): self
    {
        return new self(self::Prefix.(string) Str::ulid());
    }

    /**
     * Rehydrate a NodeId from its stored string form — durable across load/save, so a persisted
     * node keeps its identity across edits.
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
