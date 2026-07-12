<?php

use Schemastud\JsonReference\Dereferencer;
use Schemastud\JsonReference\DereferencerRegistry;
use Schemastud\JsonReference\Exceptions\UnexpectedRef;
use Schemastud\JsonReference\Exceptions\UnknownDereferencer;
use Schemastud\JsonReference\NodeId;
use Schemastud\JsonReference\Ref;
use Schemastud\JsonReference\ResolutionPolicy;
use Schemastud\JsonReference\ResolveAll;
use Schemastud\JsonReference\Vocabulary;

function stubRef(NodeId $id): Ref
{
    return new class($id) implements Ref
    {
        public function __construct(private NodeId $id) {}

        public function target(): NodeId
        {
            return $this->id;
        }
    };
}

it('dispatches a dereferencer by handle, never by node type', function () {
    $deref = new class implements Dereferencer
    {
        public function handle(): string
        {
            return 'composition';
        }

        public function dereference(Ref $ref, ResolutionPolicy $policy): mixed
        {
            return $ref->target();
        }
    };

    $registry = (new DereferencerRegistry)->register($deref);

    expect($registry->has('composition'))->toBeTrue()
        ->and($registry->for('composition'))->toBe($deref)
        ->and($registry->has('knowledge'))->toBeFalse();
});

it('throws UnknownDereferencer for an unregistered handle', function () {
    (new DereferencerRegistry)->for('knowledge');
})->throws(UnknownDereferencer::class);

it('reads the dispatch handle from the unprefixed x-dereference keyword', function () {
    $schema = ['type' => 'object', Vocabulary::Dereference => 'knowledge'];

    expect(Vocabulary::Dereference)->toBe('x-dereference')
        ->and($schema[Vocabulary::Dereference])->toBe('knowledge');
});

it('carries the offending ref on UnexpectedRef for the loud guard', function () {
    $ref = stubRef(NodeId::fromString('nod_bug'));
    $e = new UnexpectedRef($ref);

    expect($e->ref)->toBe($ref)
        ->and($e->getMessage())->toContain('nod_bug');
});

it('ships a v1 resolve-all policy marker', function () {
    expect(new ResolveAll)->toBeInstanceOf(ResolutionPolicy::class);
});
