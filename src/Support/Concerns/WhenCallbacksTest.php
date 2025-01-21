<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

covers(WhenCallbacks::class);

class WhenCallbacksTestClass
{
    use WhenCallbacks;

    public string $value = 'initial value';
}

it('applies a callback when a condition is true', function () {
    $class = new WhenCallbacksTestClass;

    expect($class->value)->toBe('initial value');

    $class->when(true, fn (WhenCallbacksTestClass $class) => $class->value = 'test value');

    expect($class->value)->toBe('test value');
});

it('does not apply a callback when a condition is false', function () {
    $class = new WhenCallbacksTestClass;

    expect($class->value)->toBe('initial value');

    $class->when(false, fn (WhenCallbacksTestClass $class) => $class->value = 'test value');

    expect($class->value)->toBe('initial value');
});

it('returns the instance', function () {
    $class = new WhenCallbacksTestClass;

    $result = $class->when(true, fn (WhenCallbacksTestClass $class) => $class->value = 'test value');

    expect($result)->toBe($class);
});
