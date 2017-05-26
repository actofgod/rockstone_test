<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\AbstractFloatType;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

abstract class AbstractFloatTypeTest extends AbstractParameterTypeInterfaceTest
{
    private static $limits = [
        AbstractFloatType::TYPE_FLOAT => [
            -3.402823465E+38, -1.175494352E-38,
            1.175494352E-38, 3.402823465E+38,
        ],
        AbstractFloatType::TYPE_DOUBLE => [
            -1.7976931348623157E+307, -2.2250738585072014E-307,
            2.2250738585072014E-307, 1.7976931348623157E+307,
        ],
    ];

    abstract protected function getTypeCode() : int;

    protected function getOptions() : array
    {
        return [
            'can_validate' => true,
            'pdo_type'     => \PDO::PARAM_STR,
            'db_escaping'  => false,
        ];
    }

    public function validateProvider() : array
    {
        $result = [
            [0, true, "'0'"],
            ['', false, null],
            [[], false, null],
            [new \stdClass(), false, null],
        ];
        $limits = self::$limits[$this->getTypeCode()];
        $result[] = [$limits[0], true, "'{$limits[0]}'"];
        $result[] = [$limits[1], true, "'{$limits[1]}'"];
        $result[] = [$limits[2], true, "'{$limits[2]}'"];
        $result[] = [$limits[3], true, "'{$limits[3]}'"];
        $result[] = [$limits[0] + 1, true, "'".($limits[0] + 1)."'"];
        $result[] = [$limits[1] - 1, true, "'".($limits[1] - 1)."'"];
        $result[] = [$limits[2] + 1, true, "'".($limits[2] + 1)."'"];
        $result[] = [$limits[3] - 1, true, "'".($limits[3] - 1)."'"];
        if ($this->getTypeCode() === AbstractFloatType::TYPE_FLOAT) {
            $result[] = [$limits[0] * 1.1, false, null];
            $result[] = [$limits[1] * 0.9, false, null];
            $result[] = [$limits[2] * 0.9, false, null];
            $result[] = [$limits[3] * 1.1, false, null];
        }
        $tmp = random_int(1, PHP_INT_MAX);
        $result[] = [$tmp, true, "'".((float)$tmp)."'"];
        $tmp = random_int(-PHP_INT_MAX, -1);
        $result[] = [$tmp, true, "'".((float)$tmp)."'"];
        return $result;
    }
}