<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Composition;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\MergesMultipleInstancesIntoAllOf;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\ReceivesParentSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use Closure;
use Illuminate\Support\Collection;

class NotKeyword extends Keyword implements MergesMultipleInstancesIntoAllOf, ReceivesParentSchema
{
    protected Schema $parentSchema;

    public function __construct(protected Closure $callback) {}

    /**
     * {@inheritdoc}
     */
    public function parentSchema(Schema $parentSchema): self
    {
        $this->parentSchema = $parentSchema;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Closure
    {
        return $this->callback;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        $notSchema = $this->parentSchema->cloneBaseStructure();

        ($this->callback)($notSchema);

        return $schema->merge(['not' => $notSchema->toArray(true)]);
    }
}
