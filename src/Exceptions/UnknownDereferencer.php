<?php

declare(strict_types=1);

namespace Rushing\JsonReference\Exceptions;

use RuntimeException;

/**
 * Thrown when a reference names a handle no dereferencer is registered for.
 */
final class UnknownDereferencer extends RuntimeException
{
    public function __construct(public readonly string $handle)
    {
        parent::__construct('No dereferencer registered for handle "'.$handle.'".');
    }
}
