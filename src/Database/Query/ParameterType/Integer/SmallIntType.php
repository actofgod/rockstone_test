<?php

namespace RockstoneTest\Database\Query\ParameterType\Integer;

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;

class SmallIntType extends AbstractIntegerType
{
    /** @inheritdoc */
    protected function getMinValue() : int
    {
        return -32768;
    }

    /** @inheritdoc */
    protected function getMaxValue() : int
    {
        return 32767;
    }
}