<?php

namespace Schemastud\JsonReference;

/**
 * The read-side verb the engines lack: materialize a referenced node under a policy. Dispatched by
 * HANDLE (like generate/ground resolve their handlers), never by node type.
 *
 * NODE-AGNOSTIC at the base — `dereference()` returns `mixed` so the contract assumes nothing about
 * a consumer's node model. An engine implementation covariantly NARROWS the return to its own
 * vocabulary (Fork A: project a foreign shape into the common vocabulary at the boundary, enforced
 * by the return type).
 */
interface Dereferencer
{
    /**
     * The target-engine handle this dereferencer answers for ('composition', 'knowledge', …).
     */
    public function handle(): string;

    public function dereference(Ref $ref, ResolutionPolicy $policy): mixed;
}
