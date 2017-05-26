<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\DateTime;

require_once __DIR__ . '/../AbstractDateTimeTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\DateTime\TimeType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractDateTimeTypeTest;

class TimeTypeTest extends AbstractDateTimeTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new TimeType();
    }

    public function validateProvider() : array
    {
        $result = [
            [0, true, "'00:00:00'"],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
            ['-224:23:12', true, "'-224:23:12'"],
            ['224:23:12', true, "'224:23:12'"],
        ];
        $interval = (new \DateTime())->diff(new \DateTime('-1 day'));
        $result[] = [$interval, true, "'-24:00:00'"];
        $result[] = [$interval->format("%H:%I:%S"), true, $interval->format("'%H:%I:%S'")];
        $interval = (new \DateTime())->diff(new \DateTime('+1 day'));
        $result[] = [$interval, true, "'24:00:00'"];
        $result[] = [$interval->format("%H:%I:%S"), true, $interval->format("'%H:%I:%S'")];
        $time = 19185;
        $result[] = [$time, true, "'05:19:45'"];
        return $result;
    }
}