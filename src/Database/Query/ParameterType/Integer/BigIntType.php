<?php

namespace RockstoneTest\Database\Query\ParameterType\Integer;

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;

class BigIntType extends AbstractIntegerType
{
    /** @inheritdoc */
    protected function getMinValue() : int
    {
        return -9223372036854775807;
    }

    /** @inheritdoc */
    protected function getMaxValue() : int
    {
        return 9223372036854775807;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        if (($value > 1e18 || $value < -1e18) && is_float($value)) {
            return false;
        }
        $minValue = $this->getMinValue();
        if ($minValue !== null && $value < $minValue) {
            return false;
        }
        $maxValue = $this->getMaxValue();
        if ($maxValue !== null && $value > $maxValue) {
            return false;
        }
        return true;
    }
}