<?php

namespace RockstoneTest\Database;

interface ConnectionInterface
{
    /**
     * Эскейпирует строку
     * @param string $value Эскейпируемое значение
     * @return string Эскейпированная строка
     */
    function escapeString(string $value) : string;
}
