<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\String;

require_once __DIR__ . '/../AbstractStringTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\String\CharType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractStringTypeTest;

class CharTypeTest extends AbstractStringTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new CharType();
    }

    public function validateProvider() : array
    {
        $result = parent::validateProvider();
        $longString = str_pad('a', 255);
        $result[] = [$longString, false, null];
        return $result;
    }
}