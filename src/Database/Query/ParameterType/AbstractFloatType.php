<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractFloatType implements ParameterTypeInterface
{
    /**
     * Проверяет переданное значение по попадание в область валидных для типа значений
     * @param float $value Проверяемое значение
     * @return bool True если значение валидно, false если нет
     */
    abstract protected function validateRanges(float $value) : bool;

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
    public function validate($value) : bool
    {
        if (!is_numeric($value)) {
            return false;
        } elseif ($value === 0.0 || $value === 0) {
            return true;
        }
        return $this->validateRanges($value);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        return "'" . (float)$value . "'";
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return false;
    }
}