<?php

namespace BasilLangevin\LaravelDataSchemas\Actions\Concerns;

/**
 * @template TReturn
 */
trait Runnable
{
    /**
     * Run the action.
     *
     * @template T
     *
     * @param  T  ...$arguments  The arguments that will be forwarded to the handle method
     * @return TReturn The return value of the handle method
     */
    public static function run(...$arguments): mixed
    {
        $instance = app(self::class);

        /** @var TReturn */
        return $instance->handle(...$arguments);
    }
}
