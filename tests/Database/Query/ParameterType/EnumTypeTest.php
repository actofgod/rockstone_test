<?php

namespace Tests\RockstoneTest\Database\Query\ParameterType;

require_once __DIR__ . '/../AbstractParameterTypeInterfaceTest.php';

use RockstoneTest\Database\Query\ParameterType\EnumType;
use RockstoneTest\Database\Query\ParameterTypeInterface;
use Tests\RockstoneTest\Database\Query\AbstractParameterTypeInterfaceTest;

class EnumTypeTest extends AbstractParameterTypeInterfaceTest
{
    protected function getTestInstance() : ParameterTypeInterface
    {
        return new EnumType();
    }

    protected function getOptions() : array
    {
        return [
            'can_validate' => false,
            'pdo_type'     => \PDO::PARAM_STR,
            'db_escaping'  => true,
        ];
    }

    public function validateProvider() : array
    {
        return [
            [true, false, null],
            [false, false, null],
            [1, true, "1"],
            [2, true, "2"],
            [-1, true, "-1"],
            [0, true, "0"],
            ['true', true, "true"],
            ['TRUE', true, "TRUE"],
            ['false', true, "false"],
            ['FALSE', true, "FALSE"],
            [null, false, null],
            [[], false, null],
            [new \stdClass(), false, null],
            ['', true, ""],
            [1.3, true, "1.3"],
            [0.0, true, "0"],
        ];
    }

    public function testSetOptions()
    {
        $instance = new EnumType();
        self::assertFalse($instance->canValidate());
        
        $instance->setOptions('value');
        self::assertTrue($instance->canValidate());
        self::assertTrue($instance->validate('value'));
        self::assertFalse($instance->validate('value_test'));

        $instance->setOptions('value', 'test');
        self::assertTrue($instance->canValidate());
        self::assertTrue($instance->validate('value'));
        self::assertTrue($instance->validate('test'));
        self::assertFalse($instance->validate('value_test'));

        $instance->setOptions(['Y', 'N']);
        self::assertTrue($instance->canValidate());
        self::assertTrue($instance->validate('Y'));
        self::assertTrue($instance->validate('N'));
        self::assertFalse($instance->validate('value_test'));
        self::assertFalse($instance->validate('y'));
        self::assertFalse($instance->validate('n'));
    }
}
