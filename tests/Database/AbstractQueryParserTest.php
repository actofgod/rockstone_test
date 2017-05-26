<?php

namespace Tests\RockstoneTest\Database;

use PHPUnit\Framework\TestCase;
use RockstoneTest\Database\QueryParserInterface;

abstract class AbstractQueryParserTest extends TestCase
{
    /**
     * @return QueryParserInterface
     */
    abstract protected function getTestInstance() : QueryParserInterface;

    /**
     * @dataProvider dataProvider
     * @param string $sourceQuery
     * @param string $queryForPrepare
     */
    public function testParseQuery(string $sourceQuery, string $queryForPrepare)
    {
        $instance = $this->getTestInstance();
        $query = $instance->parseQuery($sourceQuery);
        self::assertEquals($queryForPrepare, $query->getSqlQueryForPrepare());
    }

    /**
     * @return array
     */
    public function dataProvider() : array
    {
        return [
            ['SELECT * FROM users', 'SELECT * FROM users'],
            ['SELECT * FROM `users`', 'SELECT * FROM `users`'],
            ['SELECT * FROM users WHERE id = { id }', 'SELECT * FROM users WHERE id = :id'],
            ['SELECT * FROM users WHERE id = {id}', 'SELECT * FROM users WHERE id = :id'],
            ['SELECT * FROM users WHERE id = {id:int}', 'SELECT * FROM users WHERE id = :id'],
            ['SELECT * FROM users WHERE id = { id : tinyint }', 'SELECT * FROM users WHERE id = :id'],
        ];
    }
}