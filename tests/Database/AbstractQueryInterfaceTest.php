<?php

namespace Tests\RockstoneTest\Database;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\QueryInterface;

abstract class AbstractQueryInterfaceTest extends TestCase
{
    /**
     * @param string $source
     * @param array $parameters
     * @param string $prepared
     * @return QueryInterface
     */
    abstract protected function getTestInstance(string $source, array $parameters, string $prepared = '') : QueryInterface;

    /**
     * @return array
     */
    abstract public function dataProvider() : array;

    /**
     * @dataProvider dataProvider
     * @param string $source
     * @param array $parameters
     */
    public function testBindValue(string $source, array $parameters)
    {
        $instance = $this->getTestInstance($source, $parameters);
        foreach ($parameters as $name => $param) {
            self::assertSame($instance, $instance->bindValue($name, $param['validValue']));
        }
        try {
            $instance->bindValue('not_exists', 1);
        } catch (\Exception $e) {
            self::assertInstanceOf(\RuntimeException::class, $e);
            if (!empty($parameters)) {
                try {
                    $param = reset($parameters);
                    $name = key($parameters);
                    $instance->bindValue($name, $param['invalidValue']);
                } catch (\InvalidArgumentException $e) {
                    return;
                }
                self::fail('Exception not thrown for invalid value parameter');
            }
            return;
        }
        self::fail('Exception not thrown for not exists parameter');
    }

    /**
     * @dataProvider dataProvider
     * @param string $source
     * @param array $parameters
     */
    public function testBindValues(string $source, array $parameters)
    {
        $instance = $this->getTestInstance($source, $parameters);
        $params = [];
        foreach ($parameters as $name => $param) {
            $params[$name] = $param['validValue'];
        }
        self::assertSame($instance, $instance->bindValues($params));
        self::assertSame($instance, $instance->bindValues($params));

        try {
            $params['not_exists'] = 1;
            $instance->bindValues($params);
        } catch (\Exception $e) {
            unset($params['not_exists']);
            if (!empty($params)) {
                try {
                    $param = reset($parameters);
                    $name = key($parameters);
                    $params[$name] = $param['invalidValue'];
                    $instance->bindValues($params);
                } catch (\InvalidArgumentException $e) {
                    return;
                }
                self::fail('Exception not thrown for invalid value parameter');
            }
            return;
        }
        self::fail('Exception not thrown for not exists parameter');
    }

    /**
     * @dataProvider dataProvider
     * @param string $source
     * @param array $parameters
     * @param string $prepared
     */
    public function testGetSqlQueryForPrepare(string $source, array $parameters, string $prepared)
    {
        $instance = $this->getTestInstance($source, $parameters, $prepared);
        self::assertEquals($prepared, $instance->getSqlQueryForPrepare());
    }

    /**
     * @dataProvider dataProvider
     * @param string $source
     * @param array $parameters
     * @param string $prepared
     * @param string $result
     */
    public function testGetSqlQueryForExecute(string $source, array $parameters, string $prepared, string $result)
    {
        $instance = $this->getTestInstance($source, $parameters, $prepared);
        $params = [];
        foreach ($parameters as $name => $param) {
            $params[$name] = $param['validValue'];
        }
        self::assertEquals($result, $instance->getSqlQueryForExecute($params));
        $instance->bindValues($params);
        self::assertEquals($result, $instance->getSqlQueryForExecute());
    }

    /**
     * @dataProvider dataProvider
     * @param string $source
     * @param array $parameters
     */
    public function testReset(string $source, array $parameters)
    {
        $instance = $this->getTestInstance($source, $parameters);
        $params = [];
        foreach ($parameters as $name => $param) {
            $params[$name] = $param['validValue'];
        }
        self::assertEquals($instance, $instance->bindValues($params));
        $instance->reset();
        if (empty($parameters)) {
            $instance->getSqlQueryForExecute();
        } else {
            try {
                $instance->getSqlQueryForExecute();
            } catch (\Exception $e) {
                return;
            }
            self::fail('Exception not throws in testReset');
        }
    }
}