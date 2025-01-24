<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Filled;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\NotRegex;

covers(UnionSchema::class);

uses(TestsSchemaTransformation::class);

it('supports titles and descriptions')
    ->expect(fn () => $this->class->addProperty('string | int', 'property', [Title::class => 'Property', Description::class => 'This is a property']))
    ->toHaveSchema('property', [
        'type' => ['string', 'integer'],
        'title' => 'Property',
        'description' => 'This is a property',
    ]);

it('supports default values')
    ->expect(fn () => $this->class->addProperty('string | int', 'property', [], 'test'))
    ->toHaveSchema('property', [
        'type' => ['string', 'integer'],
        'default' => 'test',
    ]);

it('supports custom annotations')
    ->expect(fn () => $this->class->addProperty('string | int', 'property', [CustomAnnotation::class => ['annotation', 'test value']]))
    ->toHaveSchema('property', [
        'type' => ['string', 'integer'],
        'x-annotation' => 'test value',
    ]);

it('accepts keywords for each of its types')
    ->expect(fn () => $this->class->addProperty('string | int', 'property', [Min::class => 42]))
    ->toHaveSchema('property', [
        'type' => ['string', 'integer'],
        'minLength' => 42,
        'minimum' => 42,
    ]);

todo('supports multiple not composition keywords')
    ->expect(fn () => $this->class->addProperty('string | int', 'property', [Filled::class, NotRegex::class => '/test/']))
    ->toHaveSchema('property', [
        'type' => ['string', 'integer'],
        'not' => ['const' => 0],
        'minLength' => 1,
        'pattern' => '/test/',
    ]);

it('can clone its base structure', function () {
    $this->class->addProperty('string|int', 'name');
    $property = $this->class->getClassProperty('name');

    $schema = UnionSchema::make('test');
    $schema->applyType($property);

    $schema->description('test description');

    $clone = $schema->cloneBaseStructure();

    expect($clone)->toBeInstanceOf(UnionSchema::class);
    expect($clone->getName())->toBe('');
    expect(fn () => $clone->getDescription())->toThrow(\Exception::class, 'The keyword "description" has not been set.');

    $constituents = $clone->getConstituentSchemas();
    expect($constituents->first())->toBeInstanceOf(StringSchema::class);
    expect($constituents->last())->toBeInstanceOf(IntegerSchema::class);
});
