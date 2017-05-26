<?php

namespace Tests\RockstoneTest\Database\Query;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\Query\ParameterInterface;
use RockstoneTest\Database\Query\ParameterTypeInterface;

abstract class AbstractParameterInterfaceTest extends TestCase
{
    abstract public function getTestInstance(string $name, ParameterTypeInterface $type) : ParameterInterface;

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     */
    public function testGetName(string $name, ParameterTypeInterface $type)
    {
        $instance = $this->getTestInstance($name, $type);
        self::assertEquals($name, $instance->getName());
    }

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     */
    public function testGetType(string $name, ParameterTypeInterface $type)
    {
        $instance = $this->getTestInstance($name, $type);
        self::assertSame($type, $instance->getType());
    }

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     * @param mixed $value
     */
    public function testSetValue(string $name, ParameterTypeInterface $type, $value)
    {
        $instance = $this->getTestInstance($name, $type);
        self::assertSame($instance, $instance->setValue($value));
        self::assertEquals($value, $instance->getValue(false));
    }

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     * @param mixed $value
     */
    public function testIsValueSet(string $name, ParameterTypeInterface $type, $value)
    {
        $instance = $this->getTestInstance($name, $type);
        self::assertFalse($instance->isValueSet());
        $instance->setValue($value);
        self::assertTrue($instance->isValueSet());
    }

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     * @param mixed $value
     * @param mixed $escapedValue
     */
    public function getValue(string $name, ParameterTypeInterface $type, $value, $escapedValue)
    {
        $instance = $this->getTestInstance($name, $type);
        try {
            $instance->getValue();
        } catch (\BadMethodCallException $e) {
            $instance->setValue($value);
            self::assertEquals($escapedValue, $instance->getValue());
            self::assertEquals($value, $instance->getValue(false));
        }
    }

    /**
     * @dataProvider dataProvider
     * @param string $name
     * @param ParameterTypeInterface $type
     * @param mixed $value
     */
    public function resetValue(string $name, ParameterTypeInterface $type, $value)
    {
        $instance = $this->getTestInstance($name, $type);
        self::assertSame($instance, $instance->resetValue());
        $instance->setValue($value);
        self::assertSame($instance, $instance->resetValue());
        self::assertFalse($instance->isValueSet());
    }

    public function dataProvider()
    {
        $records = [];
        $values = [
            true, false, '', 'test', 1, 0, 1.1, -3.2, new \DateTime()
        ];
        $escaped = [
            'TRUE', 'FALSE', "''", "'test'", 1, 0, "'1.1'", -3.2, date("'Y-m-d H:i:s'")
        ];
        for ($i = 0; $i < count($values); $i++) {
            $type = $this->getTypeMock($values[$i], $escaped[$i]);
            $records[] = [
                uniqid(),
                $type,
                $values[$i],
                $escaped[$i]
            ];
        }
        return $records;
    }

    protected function getTypeMock($value, $escaped) : ParameterTypeInterface
    {
        $typeMock = $this->getMockBuilder(ParameterTypeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $typeMock->method('canValidate')->willReturn(true);

        $typeMock->method('validate')->willReturnCallback(function ($key) use($value) {
            return $value === $key;
        });

        $typeMock->method('escapeValue')->willReturnArgument($escaped);
        $typeMock->method('getPdoTypeCode')->willReturn(\PDO::PARAM_STR);
        $typeMock->method('databaseEscapingNeeded')->willReturn(false);

        return $typeMock;
    }
}