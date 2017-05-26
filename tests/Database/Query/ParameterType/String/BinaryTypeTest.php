<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\String;

require_once __DIR__ . '/CharTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\String\BinaryType;
use RockstoneTest\Database\Query\ParameterTypeInterface;

class BinaryTypeTest extends CharTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new BinaryType();
    }
}
