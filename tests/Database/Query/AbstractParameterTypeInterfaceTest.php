<?php

namespace Tests\RockstoneTest\Database\Query;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractParameterTypeInterfaceTest extends TestCase
{
    abstract protected function getTestInstance() : ParameterTypeInterface;
    abstract protected function getOptions() : array;
    abstract public function validateProvider() : array;

    public function testCanValidate()
    {
        $instance = $this->getTestInstance();
        $options = $this->getOptions();
        self::assertSame($options['can_validate'], $instance->canValidate());
    }

    public function testGetPdoTypeCode()
    {
        $instance = $this->getTestInstance();
        $options = $this->getOptions();
        self::assertSame($options['pdo_type'], $instance->getPdoTypeCode(null));
    }

    public function testDatabaseEscapingNeeded()
    {
        $instance = $this->getTestInstance();
        $options = $this->getOptions();
        self::assertSame($options['db_escaping'], $instance->databaseEscapingNeeded());
    }

    /**
     * @dataProvider validateProvider
     * @param mixed $value
     * @param bool $isValid
     */
    public function testValidate($value, bool $isValid)
    {
        $instance = $this->getTestInstance();
        self::assertSame($isValid, $instance->validate($value));
    }

    /**
     * @dataProvider validateProvider
     * @param mixed $value
     * @param bool $isValid
     * @param mixed $expected
     */
    public function testEscapeValue($value, bool $isValid, $expected)
    {
        $instance = $this->getTestInstance();
        if ($isValid) {
            self::assertEquals($expected, $instance->escapeValue($value));
        } else {
            self::assertFalse($instance->validate($value));
        }
    }
}