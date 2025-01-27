<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * @property array<PetData> $pets
 */
class HouseholdData extends Data
{
    public function __construct(
        #[Uuid]
        public string $id,
        public ?AddressData $address,
    ) {}
}
