<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Float;

require_once __DIR__ . '/../AbstractFloatTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Float\FloatType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractFloatTypeTest;

class FloatTypeTest extends AbstractFloatTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new FloatType();
    }

    protected function getTypeCode() : int
    {
        return FloatType::TYPE_FLOAT;
    }
}