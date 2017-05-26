<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractIntegerType implements ParameterTypeInterface
{
    /**
     * Возвращает минимальное знечение для текущего типа данных
     * @return int Минимальное значение
     */
    abstract protected function getMinValue() : int;

    /**
     * Возвращает максимальное знечение для текущего типа данных
     * @return int Минимальное значение
     */
    abstract protected function getMaxValue() : int;

    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_INT;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if (!is_numeric($value)) {
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

    /** @inheritdoc */
    public function canValidate() : bool
    {
        return true;
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        return (int) $value;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return false;
    }
}