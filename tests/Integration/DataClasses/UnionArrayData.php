<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Data;

class UnionArrayData extends Data
{
    public string $type = 'text';

    public string $text;

    /** @var array<int, string|int> */
    public array $marks;
}
