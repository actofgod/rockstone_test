<?php

namespace Tests\RockstoneTest\Database;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\ConnectionInterface;

abstract class AbstractConnectionInterfaceTest extends TestCase
{
    /**
     * @return ConnectionInterface
     */
    abstract protected function getTestInstance() : ConnectionInterface;

    /**
     * @dataProvider dataProvider
     * @param string $value
     * @param string $escapedValue
     */
    public function testEscapeString(string $value, string $escapedValue)
    {
        $instance = $this->getTestInstance();
        self::assertEquals($escapedValue, $instance->escapeString($value));
    }

    /**
     * @return array
     */
    public function dataProvider() : array
    {
        return [
            ['', "''"],
            ['test', "'test'"],
            ['o\'rly', "'o\\'rly'"],
            [0, "'0'"],
            [1234, "'1234'"],
            [1.1, "'1.1'"],
            [true, "'1'"],
            [false, "''"],
        ];
    }
}