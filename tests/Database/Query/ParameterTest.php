<?php

namespace Tests\RockstoneTest\Database\Query;

require_once __DIR__ . '/AbstractParameterInterfaceTest.php';

use RockstoneTest\Database\Query\Parameter;
use RockstoneTest\Database\Query\ParameterInterface;
use RockstoneTest\Database\Query\ParameterTypeInterface;

class ParameterTest extends AbstractParameterInterfaceTest
{
    public function getTestInstance(string $name, ParameterTypeInterface $type) : ParameterInterface
    {
        return new Parameter($name, $type);
    }
}
