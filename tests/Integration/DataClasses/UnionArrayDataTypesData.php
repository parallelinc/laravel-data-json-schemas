<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Data;

class UnionArrayDataTypesData extends Data
{
    public string $type = 'text';

    public string $text;

    /** @var array<int, AddressData|PetData> */
    public array $marks;
}
