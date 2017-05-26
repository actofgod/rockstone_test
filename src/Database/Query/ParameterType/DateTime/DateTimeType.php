<?php

namespace RockstoneTest\Database\Query\ParameterType\DateTime;

use RockstoneTest\Database\Query\ParameterType\AbstractDateTimeType;

class DateTimeType extends AbstractDateTimeType
{
    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($value instanceof \DateTime || is_numeric($value)) {
            return true;
        }
        if (!is_string($value)) {
            return false;
        }
        return preg_match('/^\d\d\d\d-\d\d-\d\d \d\d\:\d\d\:\d\d$/', $value);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('\'Y-m-d H:i:s\'');
        } elseif (is_numeric($value)) {
            return date('\'Y-m-d H:i:s\'', $value);
        }
        return "'{$value}'";
    }
}
