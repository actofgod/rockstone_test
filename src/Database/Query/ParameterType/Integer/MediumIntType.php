<?php

namespace RockstoneTest\Database\Query\ParameterType\Integer;

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;

class MediumIntType extends AbstractIntegerType
{
    /** @inheritdoc */
    protected function getMinValue() : int
    {
        return -8388608;
    }

    /** @inheritdoc */
    protected function getMaxValue() : int
    {
        return 8388607;
    }
}