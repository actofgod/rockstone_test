<?php

namespace RockstoneTest\Database;

interface QueryParserInterface
{
    /**
     * Создаёт объект запроса из строки
     * @param string $query Строка с SQL запросом
     * @return QueryInterface Объект запроса
     */
    public function parseQuery(string $query) : QueryInterface;
}
