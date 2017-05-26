<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\Integer;

require_once __DIR__ . '/../AbstractIntegerTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\Integer\MediumIntType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractIntegerTypeTest;

class MediumIntTypeTest extends AbstractIntegerTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new MediumIntType();
    }

    protected function getTypeCode() : int
    {
        return MediumIntType::TYPE_MEDIUMINT;
    }
}