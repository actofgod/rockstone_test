<?php

namespace RockstoneTest\Database;

class FakeDatabaseConnection implements ConnectionInterface
{
    /**
     * Эскейпирует строку, в реальном приложении, естественно, метод должен делать что-то типа $pdo->quote()
     * @param string $value Эскейпируемое значение
     * @return string Эскейпированная строка
     */
    public function escapeString(string $value) : string
    {
        return "'".addslashes($value)."'";
    }
}
