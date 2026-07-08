<?php

namespace Rushing\JsonReference;

/**
 * The base/standard reference vocabulary this leaf owns — the small set of cross-engine keywords
 * that, like JSON Schema's `$ref`, are NOT engine-prefixed. Engine-private keywords take the
 * `x-swc-*` / `x-swk-*` namespacing; the vocabulary here stays unprefixed and base-owned so a
 * reference handle means the same thing across every engine.
 */
class Vocabulary
{
    /**
     * JSON-LD instance identity — the serialized form of a node's {@see NodeId}. `@id` (not the
     * `$`-prefixed JSON Schema convention) so it is safe to persist in stores that reject `$` keys.
     */
    public const Id = '@id';

    /**
     * The dereference dispatch keyword — its VALUE is the target-engine handle ('composition',
     * 'knowledge', …) a {@see DereferencerRegistry} resolves. Unprefixed and base-owned, like `@id`
     * and JSON Schema's `$ref`: a cross-engine handle only works if the keyword carrying it means
     * the same thing everywhere.
     */
    public const Dereference = 'x-dereference';

    /**
     * One-line descriptions of the base keywords this leaf owns, keyed by keyword string. The single
     * authoritative source of the base vocabulary's *meaning* for tooling that describes it (e.g. the
     * composition generation-grammar export unions these in) — the name lives on the const above, its
     * meaning lives here, so neither is re-authored by a consuming engine.
     *
     * @return array<string, string>
     */
    public static function descriptions(): array
    {
        return [
            self::Dereference => 'Base-owned, unprefixed dispatch handle (like $ref): names the Dereferencer that resolves this node. Shared across engines; canonical home is the laravel-json-reference leaf.',
        ];
    }
}
