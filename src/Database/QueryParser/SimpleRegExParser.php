<?php

namespace RockstoneTest\Database\QueryParser;

use RockstoneTest\Database\Query\Parameter;
use RockstoneTest\Database\Query\ParameterType\EnumType;
use RockstoneTest\Database\Query\ParameterType\ValueBasedType;
use RockstoneTest\Database\QueryInterface;
use RockstoneTest\Database\QueryParser;
use RockstoneTest\Database\QueryParserInterface;

class SimpleRegExParser implements QueryParserInterface
{
    /** @var QueryParser */
    private $baseParser;

    /**
     * @param QueryParser $baseParser
     */
    public function __construct(QueryParser $baseParser)
    {
        $this->baseParser = $baseParser;
    }

    /** @inheritdoc */
    public function parseQuery(string $query) : QueryInterface
    {
        $regEx = '/\{\s*(\w+)(\s*\:\s*(\w+))?\s*\}/';
        $requiredParameters = [];
        if (preg_match_all($regEx, $query, $matches)) {
            $replaceMap = [];
            foreach ($matches[0] as $index => $replaceValue) {
                if (empty($matches[1][$index])) {
                    throw new \RangeException('Name for parameter#' . $index . ' not specified');
                }
                $parameterName = $matches[1][$index];
                $typeName = null;
                if (!empty($matches[3][$index])) {
                    $typeName = $matches[3][$index];
                }
                if ($typeName === null) {
                    $type = new ValueBasedType($this->baseParser);
                } elseif (is_numeric($typeName)) {
                    $type = $this->baseParser->getTypeByCode($typeName);
                } elseif ($typeName === 'enum') {
                    $type = new EnumType();
                } else {
                    $type = $this->baseParser->getTypeByAlias($typeName);
                }
                $parameter = new Parameter($parameterName, $type);
                $requiredParameters[$parameter->getName()] = $parameter;
                $replaceMap[$replaceValue] = ':' . $parameter->getName();
            }
            $parsedQuery = strtr($query, $replaceMap);
        } else {
            $parsedQuery = $query;
        }
        return new SimpleRegExQuery(
            $this->baseParser->getDatabaseConnection(), $query, $parsedQuery, $requiredParameters
        );
    }
}