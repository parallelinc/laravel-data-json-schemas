<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use Spatie\LaravelData\Data;

covers(ClassWrapper::class);

#[Title('Test'), CustomAnnotation('test1', 'value1'), CustomAnnotation('test2', 'value2')]
class TestClassWrapperClass
{
    public string $test;

    public int $testInt;

    public bool $testBoolean;

    protected string $hidden;

    public function test()
    {
        return 'test';
    }
}

it('can get its name')
    ->expect(ClassWrapper::make(TestClassWrapperClass::class)->getName())
    ->toBe('TestClassWrapperClass');

it('can get its short name')
    ->expect(ClassWrapper::make(TestClassWrapperClass::class)->getShortName())
    ->toBe('TestClassWrapperClass');

it('can check if it is a data object', function () {
    class IsDataObjectTestClass extends Data {}
    $reflector = ClassWrapper::make(IsDataObjectTestClass::class);

    expect($reflector->isDataObject())->toBe(true);
});

it('can check if it is not a data object', function () {
    class IsNotDataObjectTestClass {}
    $reflector = ClassWrapper::make(IsNotDataObjectTestClass::class);

    expect($reflector->isDataObject())->toBe(false);
});

it('can check if it has an attribute', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->hasAttribute(Title::class))->toBe(true);
});

it('can get an attribute', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->getAttribute(Title::class))
        ->toBeInstanceOf(AttributeWrapper::class)
        ->getValue()->toBe('Test');
});

it('can get multiple attributes of the same type', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->getAttribute(CustomAnnotation::class))
        ->toBeCollection()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(AttributeWrapper::class);
});

it('can get its properties', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->properties())
        ->toBeCollection()
        ->toHaveCount(3)
        ->each->toBeInstanceOf(PropertyWrapper::class);

    expect($reflector->properties()->first()->getName())
        ->toBe('test');
});

it('can get a property', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->getProperty('test'))
        ->toBeInstanceOf(PropertyWrapper::class)
        ->getName()->toBe('test');
});
