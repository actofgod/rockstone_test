<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Float;

require_once __DIR__ . '/../AbstractFloatTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Float\DoubleType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractFloatTypeTest;

class DoubleTypeTest extends AbstractFloatTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new DoubleType();
    }

    protected function getTypeCode() : int
    {
        return DoubleType::TYPE_DOUBLE;
    }
}