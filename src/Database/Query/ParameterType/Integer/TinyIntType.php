<?php

namespace RockstoneTest\Database\Query\ParameterType\Integer;

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;

class TinyIntType extends AbstractIntegerType
{
    /** @inheritdoc */
    protected function getMinValue() : int
    {
        return -128;
    }

    /** @inheritdoc */
    protected function getMaxValue() : int
    {
        return 127;
    }
}