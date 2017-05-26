<?php

namespace Tests\RockstoneTest\Database;

require_once __DIR__ . '/AbstractConnectionInterfaceTest.php';

use RockstoneTest\Database\ConnectionInterface;
use RockstoneTest\Database\FakeDatabaseConnection;

class FakeDatabaseConnectionTest extends AbstractConnectionInterfaceTest
{
    /**
     * @return ConnectionInterface
     */
    protected function getTestInstance() : ConnectionInterface
    {
        return new FakeDatabaseConnection();
    }
}