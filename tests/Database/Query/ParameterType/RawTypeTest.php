<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\RawType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

class RawTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new RawType();
    }

    protected function getOptions() : array
    {
        return [
            'can_validate' => false,
            'pdo_type'     => \PDO::PARAM_STR,
            'db_escaping'  => false,
        ];
    }

    public function validateProvider() : array
    {
        return [
            [true, true, true],
            [false, true, false],
            [1, true, 1],
            [2, true, 2],
            [-1, true, -1],
            [0, true, 0],
            ['true', true, 'true'],
            ['TRUE', true, 'TRUE'],
            ['false', true, 'false'],
            ['FALSE', true, 'FALSE'],
            [null, true, null],
            [[], true, []],
            [new \stdClass(), true, new \stdClass()],
            ['', true, ''],
            [1.3, true, 1.3],
            [0.0, true, 0.0],
        ];
    }
}