<?php

use Rushing\Popcorn\Registries\Registry;
use Rushing\Popcorn\Registries\RegistryIndex;
use Schemastud\JsonReference\Dereferencer;
use Schemastud\JsonReference\DereferencerRegistry;
use Schemastud\JsonReference\Ref;
use Schemastud\JsonReference\ResolutionPolicy;

function kernelDereferencer(string $handle): Dereferencer
{
    return new class($handle) implements Dereferencer
    {
        public function __construct(private string $handle) {}

        public function handle(): string
        {
            return $this->handle;
        }

        public function dereference(Ref $ref, ResolutionPolicy $policy): mixed
        {
            return $ref->target();
        }
    };
}

// The tripwire (registry-kernel ticket 27 D3). An unshared RegistryIndex is auto-resolvable, so the
// describe assertion below would pass over a throwaway index. This is the assertion that cannot.
it('shares one RegistryIndex across the container', function () {
    expect(app(RegistryIndex::class))->toBe(app(RegistryIndex::class));
});

it('describes jsonref.dereferencers into the index at boot', function () {
    $index = app(RegistryIndex::class);

    expect($index->has('jsonref.dereferencers'))->toBeTrue()
        ->and($index->resolve('jsonref.dereferencers'))->toBe(app(DereferencerRegistry::class));
});

it('round-trips a dereferencer through the port vocabulary and the contract alike', function () {
    $deref = kernelDereferencer('fragment-corpus');
    $registry = (new DereferencerRegistry)->register($deref);

    expect($registry)->toBeInstanceOf(Registry::class)
        ->and($registry->for('fragment-corpus'))->toBe($deref)
        ->and($registry->resolve('fragment-corpus'))->toBe($deref)
        ->and($registry->tryResolve('knowledge'))->toBeNull()
        ->and($registry->handles())->toBe(['fragment-corpus'])
        ->and((string) $registry->keys()[0])->toBe('jsonref.dereferencers.fragment-corpus');
});

it('supersedes a re-registered handle, last registrant wins', function () {
    $first = kernelDereferencer('web-page');
    $second = kernelDereferencer('web-page');

    $registry = (new DereferencerRegistry)->register($first)->register($second);

    expect($registry->for('web-page'))->toBe($second)
        ->and($registry->keys())->toHaveCount(1);
});
