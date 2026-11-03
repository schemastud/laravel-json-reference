# schemastud/laravel-json-reference

A **node-type-agnostic JSON reference/identity leaf**: `NodeId` (`@id`) instance identity plus the
base cross-engine reference vocabulary every engine's resolution primitive builds on. Like JSON
Schema's `$ref`, its keywords are unprefixed and base-owned.

```bash
composer require schemastud/laravel-json-reference
```

The vocabulary, one file each under `src/`:

- `NodeId` — `@id` instance identity;
- `Ref` — the reference value object;
- `Identified` — the contract an identified node satisfies;
- `Dereferencer` + `DereferencerRegistry` — resolution, pluggable per reference shape;
- `ResolutionPolicy`, `ResolveAll` — how and how far resolution walks.

Engines (circuit/knowledge/grounding spines and their kin) register their own dereferencers over
this leaf rather than re-inventing identity. Start with [`AGENTS.md`](AGENTS.md); the contract
docblocks are the reference. Tests live in `tests/` (no `composer test` script yet — run the installed PHPUnit/Pest runner directly).

## Registries

This package declares one registry:

- [`jsonref.dereferencers`](docs/registries/jsonref.dereferencers.mdx): the dereferencer for each
  handle, each one knowing one place a reference can point.
