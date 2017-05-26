<?php

namespace RockstoneTest\Database\QueryParser;

use RockstoneTest\Database\ConnectionInterface;
use RockstoneTest\Database\Query\ParameterInterface;
use RockstoneTest\Database\QueryInterface;

class SimpleRegExQuery implements QueryInterface
{
    /** @var ConnectionInterface */
    private $connection;

    /** @var string */
    private $sourceQuery;

    /** @var string */
    private $parsedQuery;

    /** @var ParameterInterface[] */
    private $parameters;
    
    public function __construct(ConnectionInterface $connection, string $query, string $parsed, array $params = [])
    {
        $this->connection = $connection;
        $this->sourceQuery = $query;
        $this->parsedQuery = $parsed;
        $this->parameters = $params;
    }

    /** @inheritdoc */
    public function bindValue($parameter, $value) : QueryInterface
    {
        if (!array_key_exists($parameter, $this->parameters)) {
            throw new \RuntimeException();
        }
        $this->parameters[$parameter]->setValue($value);
        return $this;
    }

    /** @inheritdoc */
    public function bindValues(array $parameters) : QueryInterface
    {
        foreach ($parameters as $parameter => $value) {
            $this->bindValue($parameter, $value);
        }
        return $this;
    }

    /** @inheritdoc */
    public function getSqlQueryForPrepare() : string
    {
        return $this->parsedQuery;
    }

    /** @inheritdoc */
    public function getSqlQueryForExecute(array $parameters = []) : string
    {
        if (!empty($parameters)) {
            $this->bindValues($parameters);
        }
        $replacePair = [];
        foreach ($this->parameters as $parameter) {
            if (!$parameter->isValueSet()) {
                throw new \InvalidArgumentException('Parameter "'.$parameter->getName().'" not specified');
            }
            $key = ':' . $parameter->getName();
            $value = $parameter->getValue(true);
            if ($parameter->getType()->databaseEscapingNeeded()) {
                $value = $this->connection->escapeString($value);
            }
            $replacePair[$key] = $value;
        }
        if (!empty($replacePair)) {
            return strtr($this->parsedQuery, $replacePair);
        }
        return $this->parsedQuery;
    }

    /** @inheritdoc */
    public function reset() : QueryInterface
    {
        foreach ($this->parameters as $parameter) {
            $parameter->resetValue();
        }
        return $this;
    }

    /** @inheritdoc */
    public function __toString()
    {
        return $this->getSqlQueryForExecute();
    }
}