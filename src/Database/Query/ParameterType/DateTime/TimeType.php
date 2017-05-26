<?php

namespace RockstoneTest\Database\Query\ParameterType\DateTime;

use RockstoneTest\Database\Query\ParameterType\AbstractDateTimeType;

class TimeType extends AbstractDateTimeType
{
    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($value instanceof \DateInterval) {
            return true;
        }
        if (is_numeric($value)) {
            return true;
        }
        if (!is_string($value)) {
            return false;
        }
        return preg_match('/^\-?\d?\d\d\:\d\d\:\d\d$/', $value);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($value instanceof \DateInterval) {
            return ($value->invert ? "'-" : "'") . ($value->format('%a') * 24 + $value->h) . $value->format(":%I:%S'");
        } elseif (is_numeric($value)) {
            $h = (int)floor($value / 3600);
            $m = (int)floor(($value % 3600) / 60);
            $s = ($value % 3600) % 60;
            if ($h < 10) {
                $h = '0' . $h;
            }
            if ($m < 10) {
                $m = '0' . $m;
            }
            if ($s < 10) {
                $s = '0' . $s;
            }
            return "'{$h}:{$m}:{$s}'";
        }
        return "'{$value}'";
    }
}
