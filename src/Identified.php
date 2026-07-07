<?php

namespace Rushing\JsonReference;

/**
 * A node that carries a stable {@see NodeId}.
 *
 * Implemented ADDITIVELY: a node that does not implement this keeps working unchanged, and
 * serializers surface `@id` only when a node is identified. This is what lets identity land on a
 * kernel node without forcing every node type to grow an id — the leaf stays node-type-agnostic.
 */
interface Identified
{
    public function id(): NodeId;
}
