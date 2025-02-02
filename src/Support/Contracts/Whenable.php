<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Contracts;

use Closure;

interface Whenable
{
    public function when(bool $condition, Closure $callback): static;
}
