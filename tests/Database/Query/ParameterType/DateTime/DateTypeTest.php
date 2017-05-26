<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\DateTime;

require_once __DIR__ . '/../AbstractDateTimeTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\DateTime\DateType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractDateTimeTypeTest;

class DateTypeTest extends AbstractDateTimeTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new DateType();
    }

    public function validateProvider() : array
    {
        $result = [
            [0, false, null],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
        $date = new \DateTime();
        $result[] = [$date, true, $date->format("'Y-m-d'")];
        $result[] = [$date->format("Y-m-d"), true, $date->format("'Y-m-d'")];
        $time = time();
        $result[] = [$time, false, null];
        return $result;
    }
}