<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Contracts;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;

interface HasDataType
{
    public static function getDataType(): DataType;

    public function applyType(): self;
}
