<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType\DateTime;

require_once __DIR__ . '/../AbstractDateTimeTypeTest.php';

use RockstoneTest\Database\Query\ParameterType\DateTime\YearType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\ParameterType\AbstractDateTimeTypeTest;

class YearTypeTest extends AbstractDateTimeTypeTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new YearType();
    }

    public function validateProvider() : array
    {
        $result = [
            [0, true, "'0000'"],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
        $date = new \DateTime();
        $result[] = [$date, true, $date->format("'Y'")];
        $result[] = [$date->format("Y"), true, $date->format("'Y'")];
        $year = random_int(1901, 2155);
        $result[] = [$year, true, "'{$year}'"];
        $year = 1901;
        $result[] = [$year, true, "'{$year}'"];
        $year = 1902;
        $result[] = [$year, true, "'{$year}'"];
        $year = 1900;
        $result[] = [$year, false, "'{$year}'"];
        $year = 2155;
        $result[] = [$year, true, "'{$year}'"];
        $year = 2154;
        $result[] = [$year, true, "'{$year}'"];
        $year = 2156;
        $result[] = [$year, false, "'{$year}'"];
        return $result;
    }
}