<?php

use Rushing\JsonReference\NodeId;
use Rushing\JsonReference\Vocabulary;

it('mints opaque, prefixed, unique identities', function () {
    $a = NodeId::mint();
    $b = NodeId::mint();

    expect((string) $a)->toStartWith(NodeId::Prefix)
        ->and($a->equals($b))->toBeFalse()
        ->and((string) $a)->not->toBe((string) $b);
});

it('rehydrates a stored id verbatim (durable across load/save)', function () {
    $id = NodeId::fromString('nod_test123');

    expect((string) $id)->toBe('nod_test123')
        ->and($id->equals(NodeId::fromString('nod_test123')))->toBeTrue()
        ->and($id->equals(NodeId::mint()))->toBeFalse();
});

it('owns @id as the unprefixed instance-identity keyword', function () {
    expect(Vocabulary::Id)->toBe('@id');
});
