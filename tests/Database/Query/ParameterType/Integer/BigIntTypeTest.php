<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Integer;

require_once __DIR__ . '/../AbstractIntegerTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Integer\BigIntType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractIntegerTypeTest;

class BigIntTypeTest extends AbstractIntegerTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new BigIntType();
    }

    protected function getTypeCode() : int
    {
        return BigIntType::TYPE_BIGINT;
    }
}