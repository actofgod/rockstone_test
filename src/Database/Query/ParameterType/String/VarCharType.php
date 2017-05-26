<?php

namespace RockstoneTest\Database\Query\ParameterType\String;

use RockstoneTest\Database\Query\ParameterType\AbstractStringType;

class VarCharType extends AbstractStringType
{
    /** @inheritdoc */
    protected function getMaxLength() : int
    {
        return 65533;
    }
}
