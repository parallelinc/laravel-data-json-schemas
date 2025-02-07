<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\Enums;

enum ProvinceEnum: string
{
    case Alberta = 'AB';
    case BritishColumbia = 'BC';
    case Manitoba = 'MB';
    case NewBrunswick = 'NB';
    case NewfoundlandAndLabrador = 'NL';
    case NovaScotia = 'NS';
    case Ontario = 'ON';
    case PrinceEdwardIsland = 'PE';
    case Quebec = 'QC';
    case Saskatchewan = 'SK';
}
