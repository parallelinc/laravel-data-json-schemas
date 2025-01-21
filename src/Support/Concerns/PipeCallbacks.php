<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

trait PipeCallbacks
{
    public function pipe(callable $callback)
    {
        return $callback($this);
    }
}
