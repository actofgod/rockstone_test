<?php

namespace RockstoneTest\Database\Query\ParameterType\DateTime;

use RockstoneTest\Database\Query\ParameterType\AbstractDateTimeType;

class DateType extends AbstractDateTimeType
{
    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($value instanceof \DateTime) {
            return true;
        }
        if (!is_string($value)) {
            return false;
        }
        return preg_match('/^\d\d\d\d-\d\d-\d\d$/', $value);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('\'Y-m-d\'');
        }
        return "'{$value}'";
    }
}
