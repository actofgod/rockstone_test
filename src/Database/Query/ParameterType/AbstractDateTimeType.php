<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractDateTimeType implements ParameterTypeInterface
{
    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_STR;
    }

    /** @inheritdoc */
    public function canValidate() : bool
    {
        return true;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return false;
    }
}