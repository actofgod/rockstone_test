<?php

namespace RockstoneTest\Database\Query;

class Parameter implements ParameterInterface
{
    /** @var string Имя текущего парметра для подстановки в SQL Запрос */
    private $name;

    /** @var ParameterTypeInterface Тип текущего параметра */
    private $type;

    /** @var bool Было ли установлено значение параметра */
    private $valueIsSet;

    /** @var mixed Значение параметра */
    private $value;

    public function __construct(string $name, ParameterTypeInterface $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->valueIsSet = false;
        $this->value = null;
    }

    /** @inheritdoc */
    public function getName() : string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function getType() : ParameterTypeInterface
    {
        return $this->type;
    }

    /** @inheritdoc */
    public function validate($value) : bool
    {
        if ($this->type->canValidate()) {
            return $this->type->validate($value);
        }
        return true;
    }

    /** @inheritdoc */
    public function setValue($value) : ParameterInterface
    {
        if ($this->type->canValidate()) {
            if (!$this->type->validate($value)) {
                throw new \InvalidArgumentException('Invalid parameter value "'.$value.'"');
            }
        }
        $this->value = $value;
        $this->valueIsSet = true;
        return $this;
    }

    /** @inheritdoc */
    public function isValueSet() : bool
    {
        return $this->valueIsSet;
    }

    /** @inheritdoc */
    public function getValue($escaped = true)
    {
        return $escaped ? $this->type->escapeValue($this->value) : $this->value;
    }

    /** @inheritdoc */
    public function resetValue() : ParameterInterface
    {
        $this->value = null;
        $this->valueIsSet = false;
        return $this;
    }
}
