<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\BoolType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

class BoolTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new BoolType();
    }

    protected function getOptions() : array
    {
        return [
            'can_validate' => true,
            'pdo_type'     => \PDO::PARAM_INT,
            'db_escaping'  => false,
        ];
    }

    public function validateProvider() : array
    {
        return [
            [true, true, 1],
            [false, true, 0],
            [1, true, 1],
            [2, true, 1],
            [-1, true, 1],
            [0, true, 0],
            ['true', true, 1],
            ['TRUE', true, 1],
            ['false', true, 0],
            ['FALSE', true, 0],
            [null, false, null],
            [[], false, null],
            [new \stdClass(), false, null],
            ['', false, null],
            [1.3, true, 1],
            [0.0, true, 0],
        ];
    }
}