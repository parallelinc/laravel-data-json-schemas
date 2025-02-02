<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

interface ReceivesParentSchema
{
    /**
     * Set the parent schema to the class that this keyword was applied to.
     */
    public function parentSchema(Schema $parentSchema): self;
}
