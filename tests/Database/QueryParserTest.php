<?php

namespace Tests\RockstoneTest\Database;

require_once __DIR__ . '/AbstractQueryParserTest.php';

use RockstoneTest\Database\ConnectionInterface;
use RockstoneTest\Database\Query\ParameterType\AbstractDateTimeType;
use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;
use RockstoneTest\Database\Query\ParameterType\AbstractStringType;
use RockstoneTest\Database\Query\ParameterType\EnumType;
use RockstoneTest\Database\QueryParser;
use RockstoneTest\Database\QueryParserInterface;

class QueryParserTest extends AbstractQueryParserTest
{
    protected function getTestInstance() : QueryParserInterface
    {
        return new QueryParser($this->getDatabaseMock());
    }

    protected function getDatabaseMock() : ConnectionInterface
    {
        $dbMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dbMock->method('escapeString')->willReturnCallback(function ($value) {
            return "'".addslashes($value)."'";
        });

        return $dbMock;
    }

    public function testGetTypeByAlias()
    {
        $instance = new QueryParser($this->getDatabaseMock());
        self::assertInstanceOf(AbstractIntegerType::class, $instance->getTypeByAlias('tinyint'));
        self::assertInstanceOf(AbstractDateTimeType::class, $instance->getTypeByAlias('datetime'));
        self::assertInstanceOf(AbstractStringType::class, $instance->getTypeByAlias('varchar'));
    }

    public function testGetTypeByCode()
    {
        $instance = new QueryParser($this->getDatabaseMock());
        self::assertInstanceOf(AbstractIntegerType::class, $instance->getTypeByCode(AbstractIntegerType::TYPE_MEDIUMINT));
        self::assertInstanceOf(AbstractDateTimeType::class, $instance->getTypeByCode(AbstractDateTimeType::TYPE_DATE));
        self::assertInstanceOf(AbstractStringType::class, $instance->getTypeByCode(AbstractStringType::TYPE_CHAR));
    }

    public function registerType()
    {
        $instance = new QueryParser($this->getDatabaseMock());

        self::assertSame($instance, $instance->registerType(EnumType::class, 'some_enum'));
        self::assertInstanceOf(EnumType::class, $instance->getTypeByAlias('some_enum'));

        $enum = new EnumType();
        self::assertSame($instance, $instance->registerType($enum, 'another_enum'));
        self::assertInstanceOf($enum, $instance->getTypeByAlias('another_enum'));
    }
}