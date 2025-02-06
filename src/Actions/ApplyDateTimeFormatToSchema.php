<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;

class ApplyDateTimeFormatToSchema
{
    /** @use Runnable<array{StringSchema|UnionSchema}, StringSchema|UnionSchema> */
    use Runnable;

    public function handle(StringSchema|UnionSchema $schema): StringSchema|UnionSchema
    {
        return $schema->format(Format::DateTime);
    }
}
