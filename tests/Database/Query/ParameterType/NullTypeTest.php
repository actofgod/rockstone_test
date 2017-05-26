<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\NullType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

class NullTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new NullType();
    }

    protected function getOptions() : array
    {
        return [
            'can_validate' => false,
            'pdo_type'     => \PDO::PARAM_NULL,
            'db_escaping'  => false,
        ];
    }

    public function validateProvider() : array
    {
        return [
            [true, true, 'NULL'],
            [false, true, 'NULL'],
            [1, true, 'NULL'],
            [2, true, 'NULL'],
            [-1, true, 'NULL'],
            [0, true, 'NULL'],
            ['true', true, 'NULL'],
            ['TRUE', true, 'NULL'],
            ['false', true, 'NULL'],
            ['FALSE', true, 'NULL'],
            [null, true, 'NULL'],
            [[], true, 'NULL'],
            [new \stdClass(), true, 'NULL'],
            ['', true, 'NULL'],
            [1.3, true, 'NULL'],
            [0.0, true, 'NULL'],
        ];
    }
}