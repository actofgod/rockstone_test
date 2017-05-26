<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractStringType implements ParameterTypeInterface
{
    /**
     * Возвращает максимальную длину строки для текущего типа данных
     * @return int Максимальная длина строки
     */
    abstract protected function getMaxLength() : int;

    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_STR;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if (is_object($value)) {
            if (!method_exists($value, '__toString')) {
                return false;
            }
            $value = (string) $value;
        } elseif (!is_string($value) && !is_numeric($value)) {
            return false;
        }
        if (strlen($value) > $this->getMaxLength()) {
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
        return (string)$value;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return true;
    }
}
