<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\String;

require_once __DIR__ . '/../AbstractStringTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\String\VarCharType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractStringTypeTest;

class VarCharTypeTest extends AbstractStringTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new VarCharType();
    }

    public function validateProvider() : array
    {
        $result = parent::validateProvider();
        $longString = str_pad('a', 65535);
        $result[] = [$longString, false, null];
        return $result;
    }
}