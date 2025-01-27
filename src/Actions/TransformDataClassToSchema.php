<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;

class TransformDataClassToSchema
{
    use Runnable;

    public function handle(ClassWrapper $class): ObjectSchema
    {
        return ObjectSchema::make($class->getName())
            ->type('object')
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyPropertiesToDataObjectSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyRequiredToDataObjectSchema::run($schema, $class));
    }
}
