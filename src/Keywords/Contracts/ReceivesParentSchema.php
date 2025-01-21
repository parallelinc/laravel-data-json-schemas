<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

interface ReceivesParentSchema
{
    public function parentSchema(Schema $parentSchema): self;
}
