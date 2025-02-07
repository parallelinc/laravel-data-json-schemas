<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses;

use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\Enums\ProvinceEnum;
use Spatie\LaravelData\Attributes\Validation\AlphaDash;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        /** The street name and number */
        public string $street,
        #[AlphaDash]
        public string|int|null $apartment,
        #[Max(100)]
        public string $city,
        public ProvinceEnum $province,
        #[Regex('^[A-Z]\d[A-Z] \d[A-Z]\d$')]
        public string $postalCode,
    ) {}
}
