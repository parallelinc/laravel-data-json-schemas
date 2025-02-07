<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\ApplyRequiredToDataObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\ClassWrapper;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

covers(ApplyRequiredToDataObjectSchema::class);

class ApplyRequiredToDataObjectSchemaTestClass extends Data
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

/**
 * PHP constructor properties are always required if they are not nullable.
 */
it('adds required properties to the schema', function () {
    $class = ClassWrapper::make(ApplyRequiredToDataObjectSchemaTestClass::class);

    $schema = ObjectSchema::make();
    $schema = ApplyRequiredToDataObjectSchema::run($schema, $class);

    expect($schema->getRequired())->toBe([
        'requiredString',
        'stringWithDefault',
        'requiredInt',
        'presentAttribute',
        'requiredAttribute',
    ]);
});
