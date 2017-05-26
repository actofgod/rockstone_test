<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterTypeInterface;

class EnumType implements ParameterTypeInterface
{
    /** @var string[] Массив значений текущего перечисления*/
    private $validValues = [];

    /**
     * Устанавливает значения перечисления
     * @param string[] ...$enumValues Массив значений перечисления
     * @return EnumType Инстанс текущего объекта
     */
    public function setOptions(...$enumValues) : EnumType
    {
        $this->validValues = [];
        if (count($enumValues) == 1 && is_array($enumValues[0])) {
            $enumValues = $enumValues[0];
        }
        foreach ($enumValues as $value) {
            $this->validValues[$value] = true;
        }
        return $this;
    }

    /** @inheritdoc */
    public function canValidate() : bool
    {
        return !empty($this->validValues);
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if (!is_scalar($value) || is_bool($value)) {
            return false;
        }
        return empty($this->validValues) ? true : array_key_exists((string)$value, $this->validValues);
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if (!is_scalar($value)) {
            return '';
        }
        return (string)$value;
    }

    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        return \PDO::PARAM_STR;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        return true;
    }
}
