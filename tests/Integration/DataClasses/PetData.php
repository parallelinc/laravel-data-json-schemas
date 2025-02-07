<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class PetData extends Data
{
    public function __construct(
        public string $name,
        #[In(['dog', 'cat', 'bird', 'fish', 'reptile', 'other'])]
        public string $species,
        public ?string $breed,
        #[Max(100)]
        /** @var int The age of the pet in years */
        public int $age,
    ) {}
}
