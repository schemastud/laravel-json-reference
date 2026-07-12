<?php

namespace Schemastud\JsonReference;

/**
 * The v1 no-op policy: resolve everything, because there is no budget yet and every node is
 * resident. The default policy a render walk carries so the dereference step is a pass-through
 * until a real (budgeted) policy and a store exist.
 */
class ResolveAll implements ResolutionPolicy {}
