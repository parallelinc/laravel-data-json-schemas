<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

class ApplyDateTimeFormatToSchema
{
    use Runnable;

    public function handle(Schema $schema): Schema
    {
        return $schema->format(Format::DateTime);
    }
}
