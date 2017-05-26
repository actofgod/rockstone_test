<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

abstract class AbstractDateTimeTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getOptions() : array
    {
        return [
            'can_validate' => true,
            'pdo_type'     => \PDO::PARAM_STR,
            'db_escaping'  => false,
        ];
    }
}