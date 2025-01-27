<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class PersonData extends Data
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $middleName,
        #[Max(100)]
        public int $age,
    ) {}
}
