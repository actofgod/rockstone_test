<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\DateTime;

require_once __DIR__ . '/../AbstractDateTimeTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\DateTime\DateTimeType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractDateTimeTypeTest;

class DateTimeTypeTest extends AbstractDateTimeTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new DateTimeType();
    }

    public function validateProvider() : array
    {
        $result = [
            [0, true, date("'Y-m-d H:i:s'", 0)],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
        $date = new \DateTime();
        $result[] = [$date, true, $date->format("'Y-m-d H:i:s'")];
        $result[] = [$date->format("Y-m-d H:i:s"), true, $date->format("'Y-m-d H:i:s'")];
        $time = time();
        $result[] = [$time, true, date("'Y-m-d H:i:s'", $time)];
        return $result;
    }
}