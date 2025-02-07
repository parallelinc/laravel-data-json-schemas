<?php

use BasilLangevin\LaravelDataJsonSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\Description;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\Title;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Accepted;

covers(BooleanSchema::class);

uses(TestsSchemaTransformation::class);

it('supports basic boolean properties')
    ->expect(fn () => $this->class->addBooleanProperty('property'))
    ->toHaveSchema('property', [
        'type' => 'boolean',
    ]);

it('supports titles and descriptions')
    ->expect(fn () => $this->class->addBooleanProperty('property', [Title::class => 'Property', Description::class => 'This is a property']))
    ->toHaveSchema('property', [
        'type' => 'boolean',
        'title' => 'Property',
        'description' => 'This is a property',
    ]);

it('supports default values')
    ->expect(fn () => $this->class->addBooleanProperty('property', [], true))
    ->toHaveSchema('property', [
        'type' => 'boolean',
        'default' => true,
    ]);

it('supports custom annotations')
    ->expect(fn () => $this->class->addBooleanProperty('property', [CustomAnnotation::class => ['annotation', 'test value']]))
    ->toHaveSchema('property', [
        'type' => 'boolean',
        'x-annotation' => 'test value',
    ]);

it('supports enum values')
    ->expect(fn () => BooleanSchema::make()->enum([true, false]))
    ->toArray()
    ->toEqual([
        'enum' => [true, false],
    ]);

it('supports const values')
    ->expect(fn () => $this->class->addBooleanProperty('test', [Accepted::class]))
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'const' => true,
    ]);

it('supports not composition keywords')
    ->expect(fn () => BooleanSchema::make()->not(fn (BooleanSchema $schema) => $schema->const(false)))
    ->toArray()
    ->toEqual([
        'not' => ['const' => false],
    ]);
