<?php

namespace BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Data;

class VacationData extends Data
{
    public function __construct(
        public string $destination,
        #[AfterOrEqual('today')]
        public Carbon $startDate,
        public ?Carbon $endDate,
    ) {}
}
