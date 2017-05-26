<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\String;

use RockstoneTest\Database\Query\ParameterType\String\VarBinaryType;
use RockstoneTest\Database\Query\ParameterTypeInterface;

require_once __DIR__ . '/VarCharTypeTest.php';

class VarBinaryTypeTest extends VarCharTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new VarBinaryType();
    }
}