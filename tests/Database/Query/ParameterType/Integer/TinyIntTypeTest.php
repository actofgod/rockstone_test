<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Integer;

require_once __DIR__ . '/../AbstractIntegerTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Integer\TinyIntType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractIntegerTypeTest;

class TinyIntTypeTest extends AbstractIntegerTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new TinyIntType();
    }

    protected function getTypeCode() : int
    {
        return TinyIntType::TYPE_TINYINT;
    }
}