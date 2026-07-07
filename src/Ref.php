<?php

namespace Rushing\JsonReference;

/**
 * A reference: an identity, nothing more. A node that names its target by {@see NodeId} instead of
 * inlining it — the read-side shape that lets a content graph be partially resident without the
 * logical model encoding size (see the size-invariance ADR).
 *
 * Node-type-agnostic at the base: an engine's own reference node implements this and adds whatever
 * typed discriminator it needs (the composition engine's node adds `kind(): RefKind`).
 */
interface Ref
{
    /**
     * The stable logical identity of the referenced node — survives spill, move, and reorder.
     */
    public function target(): NodeId;
}
