<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

class BoolType implements ParameterTypeInterface
{
    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_INT;
    }

    /** @inheritdoc */
    public function canValidate() : bool
    {
        return true;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($value === true || $value === false || is_numeric($value)) {
            return true;
        }
        if (is_string($value) && in_array(strtolower($value), ['true', 'false'])) {
            return true;
        }
        return false;
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($value === true || is_numeric($value) && $value != 0 || strtolower($value) === 'true') {
            return 1;
        }
        return 0;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return false;
    }
}