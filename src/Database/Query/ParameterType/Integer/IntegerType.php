<?php

namespace RockstoneTest\Database\Query\ParameterType\Integer;

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;

class IntegerType extends AbstractIntegerType
{
    /** @inheritdoc */
    protected function getMinValue() : int
    {
        return -2147483648;
    }

    /** @inheritdoc */
    protected function getMaxValue() : int
    {
        return 2147483647;
    }
}