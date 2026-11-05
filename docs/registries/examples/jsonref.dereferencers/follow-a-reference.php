<?php

use Schemastud\JsonReference\Dereferencer;
use Schemastud\JsonReference\DereferencerRegistry;
use Schemastud\JsonReference\NodeId;
use Schemastud\JsonReference\Ref;
use Schemastud\JsonReference\ResolutionPolicy;
use Schemastud\JsonReference\ResolveAll;
use Schemastud\JsonReference\Vocabulary;

// A dereferencer for the `articles` handle. A real one would query a store by the node's @id.
$articles = new class implements Dereferencer
{
    public function handle(): string
    {
        return 'articles';
    }

    public function dereference(Ref $ref, ResolutionPolicy $policy): mixed
    {
        return ['@id' => (string) $ref->target(), 'title' => 'Why handles, not shapes'];
    }
};

$registry = app(DereferencerRegistry::class)->register($articles);

// The node a document carries in place of the article: its @id, and the handle that can follow it.
$node = [Vocabulary::Id => 'nod_01JC0ARTICLE0000000000002', Vocabulary::Dereference => 'articles'];

$ref = new class(NodeId::fromString($node[Vocabulary::Id])) implements Ref
{
    public function __construct(private NodeId $id) {}

    public function target(): NodeId
    {
        return $this->id;
    }
};

return [
    'keys' => array_map('strval', $registry->keys()),
    'handles' => $registry->handles(),
    'node' => $node,
    'resolved' => $registry->for($node[Vocabulary::Dereference])->dereference($ref, new ResolveAll),
];
