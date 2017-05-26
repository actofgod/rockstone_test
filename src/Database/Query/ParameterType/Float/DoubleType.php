<?php

namespace RockstoneTest\Database\Query\ParameterType\Float;

use RockstoneTest\Database\Query\ParameterType\AbstractFloatType;

class DoubleType extends AbstractFloatType
{
    /** @inheritdoc */
    protected function validateRanges(float $value) : bool
    {
        if (-1.7976931348623157E+308 < $value && $value < -2.2250738585072014E-308) {
            return true;
        } elseif (2.2250738585072014E-308 < $value && $value < 1.7976931348623157E+308) {
            return true;
        }
        return false;
    }
}
