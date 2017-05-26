<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

class NullType implements ParameterTypeInterface
{
    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_NULL;
    }

    /** @inheritdoc */
    public function canValidate() : bool
    {
        return false;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        return true;
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        return 'NULL';
    }
    
    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return false;
    }
}
