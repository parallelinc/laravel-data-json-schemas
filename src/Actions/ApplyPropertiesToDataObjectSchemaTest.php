<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyPropertiesToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

covers(ApplyPropertiesToDataObjectSchema::class);

class ApplyPropertiesToDataObjectSchemaTestClass extends Data
{
    public function __construct(
        public string $requiredString,
        public string $stringWithDefault,
        public int $requiredInt,
        public ?int $optionalInt = null,
        #[Present]
        public ?string $presentAttribute = null,
        #[Required]
        public ?string $requiredAttribute = 'optional',
    ) {}
}

it('adds properties to the schema', function () {
    $class = ClassWrapper::make(ApplyPropertiesToDataObjectSchemaTestClass::class);

    $schema = ObjectSchema::make('Test');
    ApplyPropertiesToDataObjectSchema::run($schema, $class);

    expect($schema->getProperties())->toHaveCount(6);
    expect(collect($schema->getProperties())->map->getName()->toArray())->toBe([
        'requiredString',
        'stringWithDefault',
        'requiredInt',
        'optionalInt',
        'presentAttribute',
        'requiredAttribute',
    ]);
});

it('does not add properties to the schema if there are no properties', function () {
    $class = ClassWrapper::make(Data::class);

    $schema = ObjectSchema::make('Test');
    ApplyPropertiesToDataObjectSchema::run($schema, $class);

    expect(fn () => $schema->getProperties())->toThrow(\Exception::class, 'The keyword "properties" has not been set.');
});
