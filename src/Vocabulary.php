<?php

declare(strict_types=1);

namespace Rushing\JsonReference;

/**
 * The base/standard reference vocabulary this leaf owns — the small set of cross-engine keywords
 * that, like JSON Schema's `$ref`, are NOT engine-prefixed. Engine-private keywords take the
 * `x-swc-*` / `x-swk-*` namespacing; the vocabulary here stays unprefixed and base-owned so a
 * reference handle means the same thing across every engine.
 */
final class Vocabulary
{
    /**
     * JSON-LD instance identity — the serialized form of a node's {@see NodeId}. `@id` (not the
     * `$`-prefixed JSON Schema convention) so it is safe to persist in stores that reject `$` keys.
     */
    public const Id = '@id';
}
