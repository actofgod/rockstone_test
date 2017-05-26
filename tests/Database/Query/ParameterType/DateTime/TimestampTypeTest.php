<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\DateTime;

require_once __DIR__ . '/DateTimeTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\DateTime\TimestampType;
use RockstoneTest\Database\Query\ParameterTypeInterface;

class TimestampTypeTest extends DateTimeTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new TimestampType();
    }
}