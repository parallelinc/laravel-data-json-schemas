<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\DataClassSchemaRegistry;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class TransformDataClassToSchema
{
    use Runnable;

    public function __construct(
        protected DataClassSchemaRegistry $registry,
    ) {}

    public function handle(string $dataClass, ?SchemaTree $tree = null): ObjectSchema
    {
        $tree ??= app(SchemaTree::class)->rootClass($dataClass);

        $tree->incrementDataClassCount($dataClass);

        if ($this->registry->has($dataClass)) {
            return $this->registry->get($dataClass);
        }

        $class = ClassWrapper::make($dataClass);
        $schema = ObjectSchema::make();
        $this->registry->register($dataClass, $schema);

        $schema->class($class->getName())
            ->type('object')
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyPropertiesToDataObjectSchema::run($schema, $class, $tree))
            ->pipe(fn (Schema $schema) => ApplyRequiredToDataObjectSchema::run($schema, $class))
            ->tree($tree);

        return $schema;
    }
}
