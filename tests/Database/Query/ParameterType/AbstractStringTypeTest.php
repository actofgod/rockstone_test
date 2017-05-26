<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

abstract class AbstractStringTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getOptions() : array
    {
        return [
            'can_validate' => true,
            'pdo_type'     => \PDO::PARAM_STR,
            'db_escaping'  => true,
        ];
    }

    public function validateProvider() : array
    {
        return [
            [0, true, '0'],
            [1, true, '1'],
            [0.0, true, '0'],
            [1.1, true, '1.1'],
            ['', true, ''],
            ['asd', true, 'asd'],
            [null, false, null],
            [true, false, null],
            [false, false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
    }
}