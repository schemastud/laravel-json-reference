<?php

use Rushing\Popcorn\Registries\Docs\DataExamples;

/*
 * The registry pages' data examples are captured output (docs-feedback ticket 09). This checks the committed fixtures
 * and page blocks, and re-runs each example in this test's application. It never writes: regenerate with
 * `composer docs:examples -- --write`.
 */

it('keeps every data example captured, placed and current on its page', function () {
    $examples = DataExamples::forPackage(dirname(__DIR__, 2));

    expect(array_keys($examples->cases()))->toBe(['jsonref.dereferencers/follow-a-reference'])
        ->and($examples->problems())->toBe([]);
});

it('captures the same output the page shows', function (string $id) {
    expect(DataExamples::forPackage(dirname(__DIR__, 2))->drift($id))->toBe([]);
})->with(fn () => array_keys(DataExamples::forPackage(dirname(__DIR__, 2))->cases()));
