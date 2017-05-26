<?php

namespace RockstoneTest\Database\Query\ParameterType\Float;

use RockstoneTest\Database\Query\ParameterType\AbstractFloatType;

class FloatType extends AbstractFloatType
{
    /** @inheritdoc */
    protected function validateRanges(float $value) : bool
    {
        if (-3.402823466E+38 < $value && $value < -1.175494351E-38) {
            return true;
        } elseif (1.175494351E-38 < $value && $value < 3.402823466E+38) {
            return true;
        }
        return false;
    }
}
