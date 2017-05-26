<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\FakeDatabaseConnection;
use RockstoneTest\Database\Query\ParameterType\ValueBasedType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use RockstoneTest\Database\QueryParser;

class ValueBasedTypeTest extends TestCase
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new ValueBasedType(new QueryParser(new FakeDatabaseConnection()));
    }

    public function validateProvider() : array
    {
        return [
            [true, true, 1],
            [false, true, 0],
            [1, true, 1],
            [2, true, 2],
            [-1, true, -1],
            [0, true, 0],
            ['true', true, 'true'],
            ['TRUE', true, 'TRUE'],
            ['false', true, 'false'],
            ['FALSE', true, 'FALSE'],
            [null, true, 'NULL'],
            [[], false, null],
            [new \stdClass(), false, null],
            ['', true, ''],
            [1.3, true, "'1.3'"],
            [0.0, true, "'0'"],
        ];
    }

    public function testCanValidate()
    {
        $instance = $this->getTestInstance();
        self::assertTrue($instance->canValidate());
    }

    public function testGetPdoTypeCode()
    {
        $instance = $this->getTestInstance();

        $instance->validate(null);
        self::assertSame(\PDO::PARAM_NULL, $instance->getPdoTypeCode(null));

        $instance->validate(0);
        self::assertSame(\PDO::PARAM_INT, $instance->getPdoTypeCode(0));

        $instance->validate(1);
        self::assertSame(\PDO::PARAM_INT, $instance->getPdoTypeCode(1));

        $instance->validate(1.2);
        self::assertSame(\PDO::PARAM_STR, $instance->getPdoTypeCode(1.2));

        $instance->validate(true);
        self::assertSame(\PDO::PARAM_INT, $instance->getPdoTypeCode(true));

        $instance->validate(false);
        self::assertSame(\PDO::PARAM_INT, $instance->getPdoTypeCode(false));

        $instance->validate('asd');
        self::assertSame(\PDO::PARAM_STR, $instance->getPdoTypeCode('asd'));

        $instance->validate(new \DateTime());
        self::assertSame(\PDO::PARAM_STR, $instance->getPdoTypeCode(new \DateTime()));
    }

    public function testDatabaseEscapingNeeded()
    {
        $instance = $this->getTestInstance();
        self::assertTrue($instance->databaseEscapingNeeded());

        $instance->validate(null);
        self::assertFalse($instance->databaseEscapingNeeded());

        $instance->validate(true);
        self::assertFalse($instance->databaseEscapingNeeded());

        $instance->validate(1);
        self::assertFalse($instance->databaseEscapingNeeded());

        $instance->validate(false);
        self::assertFalse($instance->databaseEscapingNeeded());

        $instance->validate('test');
        self::assertTrue($instance->databaseEscapingNeeded());

        $instance->validate(new \DateTime());
        self::assertFalse($instance->databaseEscapingNeeded());

        $instance->validate(1.3);
        self::assertFalse($instance->databaseEscapingNeeded());
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
            $instance->validate($value);
            self::assertEquals($expected, $instance->escapeValue($value));
        } else {
            self::assertFalse($instance->validate($value));
        }
    }
}