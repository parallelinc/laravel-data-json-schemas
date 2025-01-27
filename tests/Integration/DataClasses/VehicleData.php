<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses;

use Spatie\LaravelData\Data;

class VehicleData extends Data
{
    public function __construct(
        public string $make,
        public string $model,
        public int $year,
        public string $vin,
    ) {}
}
