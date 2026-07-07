<?php

namespace Rushing\JsonReference;

/**
 * The access-time resolution policy — "for this operation, resolve this frontier, this deep."
 *
 * v1 is a BARE MARKER carrying no budget. The budgeted tier (an `admits(Cursor)` predicate + an
 * `onTimeout()` degradation rule) arrives with the remote/cross-engine store as new-code-beside-old.
 * The parameter threads through {@see Dereferencer::dereference()} from day one — even though the
 * only v1 reader throws — so adding the budget later is not a signature migration.
 */
interface ResolutionPolicy {}
