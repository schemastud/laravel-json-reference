<?php

declare(strict_types=1);

namespace Rushing\JsonReference;

/**
 * A node that carries a stable {@see NodeId}.
 *
 * Implemented ADDITIVELY: a node that does not implement this keeps working unchanged, and
 * serializers surface `@id` only when a node is identified. This is what lets identity land on
 * the kernel without forcing every {@see \Rushing\CompositionEngine\Kernel\Contracts\Unit} impl
 * (or any other node type) to grow an id.
 */
interface Identified
{
    public function id(): NodeId;
}
