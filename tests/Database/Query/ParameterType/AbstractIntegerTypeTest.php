<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\AbstractIntegerType;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

abstract class AbstractIntegerTypeTest extends AbstractParameterTypeInterfaceTest
{
    private static $limits = [
        AbstractIntegerType::TYPE_BIGINT => [
            -9223372036854775807, 9223372036854775807,
        ],
        AbstractIntegerType::TYPE_INTEGER => [
            -2147483648, 2147483647,
        ],
        AbstractIntegerType::TYPE_MEDIUMINT => [
            -8388608, 8388607,
        ],
        AbstractIntegerType::TYPE_SMALLINT => [
            -32768, 32767,
        ],
        AbstractIntegerType::TYPE_TINYINT => [
            -128, 127,
        ],
    ];

    abstract protected function getTypeCode() : int;

    protected function getOptions() : array
    {
        return [
            'can_validate' => true,
            'pdo_type'     => \PDO::PARAM_INT,
            'db_escaping'  => false,
        ];
    }

    public function validateProvider() : array
    {
        $result = [
            [0, true, 0],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
        $limits = self::$limits[$this->getTypeCode()];
        $result[] = [$limits[0], true, $limits[0]];
        $result[] = [$limits[1], true, $limits[1]];
        $result[] = [$limits[0] + 1, true, $limits[0] + 1];
        $result[] = [$limits[1] - 1, true, $limits[1] - 1];
        $result[] = [$limits[0] - 1, false, null];
        $result[] = [$limits[1] + 1, false, null];
        $tmp = random_int($limits[0], $limits[1]);
        $result[] = [$tmp, true, $tmp];
        $tmp = random_int($limits[0], -1);
        $result[] = [$tmp, true, $tmp];
        $tmp = random_int(1, $limits[1]);
        $result[] = [$tmp, true, $tmp];
        return $result;
    }
}