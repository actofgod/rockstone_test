<?php

namespace Tests\RockstoneTest\Database\QueryParser;

require_once __DIR__ . '/../QueryParserTest.php';

use RockstoneTest\Database\QueryParser;
use RockstoneTest\Database\QueryParser\SimpleRegExParser;
use RockstoneTest\Database\QueryParserInterface;
use Tests\RockstoneTest\Database\QueryParserTest;

class SimpleRegExParserTest extends QueryParserTest
{
    /**
     * @return QueryParserInterface
     */
    protected function getTestInstance() : QueryParserInterface
    {
        return new SimpleRegExParser(parent::getTestInstance());
    }
}
