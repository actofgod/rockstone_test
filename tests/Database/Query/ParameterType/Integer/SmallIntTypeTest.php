<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Integer;

require_once __DIR__ . '/../AbstractIntegerTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Integer\SmallIntType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractIntegerTypeTest;

class SmallIntTypeTest extends AbstractIntegerTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new SmallIntType();
    }

    protected function getTypeCode() : int
    {
        return SmallIntType::TYPE_SMALLINT;
    }
}