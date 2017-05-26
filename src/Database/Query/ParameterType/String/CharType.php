<?php

namespace RockstoneTest\Database\Query\ParameterType\String;

use RockstoneTest\Database\Query\ParameterType\AbstractStringType;

class CharType extends AbstractStringType
{
    /** @inheritdoc */
    protected function getMaxLength() : int
    {
        return 253;
    }
}