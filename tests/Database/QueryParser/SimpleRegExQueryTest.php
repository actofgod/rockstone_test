<?php

namespace Tests\RockstoneTest\Database\QueryParser;

require_once __DIR__ . '/../AbstractQueryInterfaceTest.php';

use RockstoneTest\Database\ConnectionInterface;
use RockstoneTest\Database\Query\Parameter;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use RockstoneTest\Database\QueryInterface;
use RockstoneTest\Database\QueryParser\SimpleRegExQuery;
use Tests\RockstoneTest\Database\AbstractQueryInterfaceTest;

class SimpleRegExQueryTest extends AbstractQueryInterfaceTest
{
    /**
     * @param string $source
     * @param array $parameters
     * @param string $prepared
     * @return QueryInterface
     */
    protected function getTestInstance(string $source, array $parameters, string $prepared = '') : QueryInterface
    {
        $params = [];
        foreach ($parameters as $name => $p) {
            $params[$name] = $p['parameter'];
        }
        return new SimpleRegExQuery($this->getDatabaseConnectionMock(), $source, $prepared, $params);
    }

    /**
     * @return array
     */
    public function dataProvider() : array
    {
        $result = [];

        $result[] = [
            'SELECT * FROM test', [], 'SELECT * FROM test', 'SELECT * FROM test',
        ];
        $result[] = [
            'SELECT * FROM test WHERE id = { id }', [
                'id' => [
                    'parameter'    => new Parameter('id', $this->getParameterTypeMock(1)),
                    'validValue'   => 1,
                    'invalidValue' => 'test',
                ]
            ], 'SELECT * FROM test WHERE id = :id', 'SELECT * FROM test WHERE id = 1',
        ];
        $result[] = [
            'SELECT * FROM test WHERE id = { id } AND gourp_id = { groupId | smallint }', [
                'id' => [
                    'parameter'    => new Parameter('id', $this->getParameterTypeMock(2)),
                    'validValue'   => 2,
                    'invalidValue' => 'test',
                ],
                'groupId' => [
                    'parameter'    => new Parameter('groupId', $this->getParameterTypeMock(3)),
                    'validValue'   => 3,
                    'invalidValue' => null,
                ]
            ], 'SELECT * FROM test WHERE id = :id AND gourp_id = :groupId','SELECT * FROM test WHERE id = 2 AND gourp_id = 3',
        ];
        return $result;
    }

    protected function getDatabaseConnectionMock() : ConnectionInterface
    {
        $dbMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dbMock->method('escapeString')->willReturnCallback(function ($value) {
            return "'".addslashes($value)."'";
        });

        return $dbMock;
    }

    protected function getParameterTypeMock($validValue) : ParameterTypeInterface
    {
        $typeMock = $this->getMockBuilder(ParameterTypeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $typeMock->method('canValidate')->willReturn(true);

        $typeMock->method('validate')->willReturnCallback(function ($key) use($validValue) {
            return $validValue === $key;
        });

        $typeMock->method('escapeValue')->willReturnArgument(0);
        $typeMock->method('getPdoTypeCode')->willReturn(\PDO::PARAM_INT);
        $typeMock->method('databaseEscapingNeeded')->willReturn(false);

        return $typeMock;
    }
}