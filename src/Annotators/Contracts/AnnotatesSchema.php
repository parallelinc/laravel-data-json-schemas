<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Annotators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

interface AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema;
}
