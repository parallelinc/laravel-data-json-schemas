<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Concerns;

use Closure;

trait WhenCallbacks
{
    /**
     * Run a callback if a condition is met.
     */
    public function when(bool $condition, Closure $callback): static
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
