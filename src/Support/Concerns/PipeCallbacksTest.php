<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

covers(PipeCallbacks::class);

class PipeCallbacksTestClass
{
    use PipeCallbacks;

    public string $value = 'initial value';
}

it('can pipe itself to a callback', function () {
    $class = new PipeCallbacksTestClass;
    $result = $class->pipe(fn (PipeCallbacksTestClass $class) => $class);

    expect($result)->toBe($class);
});

it('returns the result of the callback', function () {
    $class = new PipeCallbacksTestClass;
    $result = $class->pipe(fn (PipeCallbacksTestClass $class) => $class->value);

    expect($result)->toBe('initial value');
});
