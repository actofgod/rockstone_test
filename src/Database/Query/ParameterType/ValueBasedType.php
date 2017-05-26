<?php

namespace RockstoneTest\Database\Query\ParameterType;

use RockstoneTest\Database\Query\ParameterType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use RockstoneTest\Database\Query\ParameterTypeRegistryInterface;

class ValueBasedType implements ParameterTypeInterface
{
    /** @var ParameterTypeRegistryInterface */
    private $typeRegistry;

    /** @var ParameterTypeInterface */
    private $currentType;

    /**
     * @param ParameterTypeRegistryInterface $registry
     */
    public function __construct(ParameterTypeRegistryInterface $registry)
    {
        $this->typeRegistry = $registry;
    }

    /** @inheritdoc */
    public function getPdoTypeCode($value) : int
    {
        if ($this->currentType !== null) {
            return $this->currentType->getPdoTypeCode($value);
        }
        return \PDO::PARAM_STR;
    }

    /** @inheritdoc */
    public function databaseEscapingNeeded() : bool
    {
        if ($this->currentType !== null) {
            return $this->currentType->databaseEscapingNeeded();
        }
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
        if ($value === null) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_NULL);
        } elseif ($value === false || $value === true) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_BOOL);
        } elseif (is_integer($value)) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_INTEGER);
        } elseif (is_float($value)) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_DOUBLE);
        } elseif ($value instanceof \DateTime) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_DATETIME);
        } elseif (is_string($value) || (is_object($value) && method_exists($value, '__toString'))) {
            $this->currentType = $this->typeRegistry->getTypeByCode(self::TYPE_VARCHAR);
        } else {
            return false;
        }
        return true;
    }

    /** @inheritdoc */
    public function escapeValue($value)
    {
        if ($this->currentType !== null) {
            return $this->currentType->escapeValue($value);
        }
        throw new \InvalidArgumentException('Unexpected value type ' . gettype($value));
    }
}
