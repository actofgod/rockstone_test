<?php

namespace RockstoneTest\Database\Query\ParameterType\DateTime;

use RockstoneTest\Database\Query\ParameterType\AbstractDateTimeType;

class YearType extends AbstractDateTimeType
{
    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($value instanceof \DateTime) {
            return true;
        }
        if (!is_numeric($value)) {
            return false;
        }
        return ($value === 0) || ($value >= 1901 && $value <= 2155);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($value instanceof \DateTime) {
            return "'" . $value->format('Y') . "'";
        }
        if ($value == 0) {
            return "'0000'";
        }
        return "'{$value}'";
    }
}
