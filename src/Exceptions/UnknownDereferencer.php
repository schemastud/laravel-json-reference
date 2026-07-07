<?php

namespace Rushing\JsonReference\Exceptions;

use RuntimeException;

/**
 * Thrown when a reference names a handle no dereferencer is registered for.
 */
class UnknownDereferencer extends RuntimeException
{
    public function __construct(public string $handle)
    {
        parent::__construct('No dereferencer registered for handle "'.$handle.'".');
    }
}
