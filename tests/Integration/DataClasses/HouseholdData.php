<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * @property ?array<VehicleData> $vehicles
 */
class HouseholdData extends Data
{
    public function __construct(
        #[Uuid]
        public string|int $id,

        public ?AddressData $address,

        /** @var PersonData[] */
        public array $members,

        #[DataCollectionOf(PetData::class)]
        public array $pets,

        /** @var Collection<int, VacationData> */
        public Collection $vacations,

        public ?array $vehicles,

        /** @var Collection<int, int> */
        #[Min(3)]
        public Collection $favouriteNumbers,
    ) {}
}
