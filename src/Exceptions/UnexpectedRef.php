<?php

namespace Schemastud\JsonReference\Exceptions;

use RuntimeException;
use Schemastud\JsonReference\Ref;

/**
 * Thrown when a {@see Ref} reaches a total-resolution site while everything is supposed to be
 * resident — the loud third-case guard. In v1 (nothing emits a Ref) this firing means a bug in a
 * walker or an emitter, not a missing store fetch.
 */
class UnexpectedRef extends RuntimeException
{
    public function __construct(public Ref $ref)
    {
        parent::__construct(
            'Unexpected reference to node "'.$ref->target().'" at a total-resolution site; '
            .'nothing should emit a Ref while every node is resident (v1).'
        );
    }
}
