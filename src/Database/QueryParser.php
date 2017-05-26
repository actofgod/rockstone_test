<?php

namespace RockstoneTest\Database;

use RockstoneTest\Database\Query\ParameterTypeInterface;

class QueryParser implements QueryParserInterface, Query\ParameterTypeRegistryInterface
{
    /** @var ConnectionInterface Инстанс соединения с базой данных */
    private $connection;

    /** @var QueryParserInterface Используемый парсер запросов */
    private $parser;

    /** @var int МАксимальное значение айди типа параметра запроса для текущего объекта */
    private $maxTypeId = 100;

    /** @var array Маппинг строковых обозначений типов параметров на их айди */
    private $typesAliasesMap = [
        'null'      => ParameterTypeInterface::TYPE_NULL,

        'bool'      => ParameterTypeInterface::TYPE_BOOL,
        'boolean'   => ParameterTypeInterface::TYPE_BOOL,

        'tinyint'   => ParameterTypeInterface::TYPE_TINYINT,
        'smallint'  => ParameterTypeInterface::TYPE_SMALLINT,
        'mediumint' => ParameterTypeInterface::TYPE_MEDIUMINT,
        'integer'   => ParameterTypeInterface::TYPE_INTEGER,
        'int'       => ParameterTypeInterface::TYPE_INTEGER,
        'bigint'    => ParameterTypeInterface::TYPE_BIGINT,
        'long'      => ParameterTypeInterface::TYPE_BIGINT,

        'float'     => ParameterTypeInterface::TYPE_FLOAT,
        'double'    => ParameterTypeInterface::TYPE_DOUBLE,

        'date'      => ParameterTypeInterface::TYPE_DATE,
        'datetime'  => ParameterTypeInterface::TYPE_DATETIME,
        'timestamp' => ParameterTypeInterface::TYPE_TIMESTAMP,
        'time'      => ParameterTypeInterface::TYPE_TIME,
        'year'      => ParameterTypeInterface::TYPE_YEAR,

        'str'       => ParameterTypeInterface::TYPE_VARCHAR,
        'string'    => ParameterTypeInterface::TYPE_VARCHAR,
        'varchar'   => ParameterTypeInterface::TYPE_VARCHAR,
        'char'      => ParameterTypeInterface::TYPE_CHAR,
        'binary'    => ParameterTypeInterface::TYPE_BINARY,
        'varbinary' => ParameterTypeInterface::TYPE_VARBINARY,

        'enum'      => ParameterTypeInterface::TYPE_ENUM,

        'raw'       => ParameterTypeInterface::TYPE_RAW,
        'value'     => ParameterTypeInterface::TYPE_VALUE_BASED,
    ];

    /** @var array Маппинг айди типов парамтров на имена классов этих типов */
    private $typesClassMap = [

        ParameterTypeInterface::TYPE_NULL      => Query\ParameterType\NullType::class,

        ParameterTypeInterface::TYPE_BOOL      => Query\ParameterType\BoolType::class,

        ParameterTypeInterface::TYPE_TINYINT   => Query\ParameterType\Integer\TinyIntType::class,
        ParameterTypeInterface::TYPE_SMALLINT  => Query\ParameterType\Integer\SmallIntType::class,
        ParameterTypeInterface::TYPE_MEDIUMINT => Query\ParameterType\Integer\MediumIntType::class,
        ParameterTypeInterface::TYPE_INTEGER   => Query\ParameterType\Integer\IntegerType::class,
        ParameterTypeInterface::TYPE_BIGINT    => Query\ParameterType\Integer\BigIntType::class,

        ParameterTypeInterface::TYPE_FLOAT     => Query\ParameterType\Float\FloatType::class,
        ParameterTypeInterface::TYPE_DOUBLE    => Query\ParameterType\Float\DoubleType::class,

        ParameterTypeInterface::TYPE_DATE      => Query\ParameterType\DateTime\DateType::class,
        ParameterTypeInterface::TYPE_DATETIME  => Query\ParameterType\DateTime\DateTimeType::class,
        ParameterTypeInterface::TYPE_TIMESTAMP => Query\ParameterType\DateTime\TimestampType::class,
        ParameterTypeInterface::TYPE_TIME      => Query\ParameterType\DateTime\TimeType::class,
        ParameterTypeInterface::TYPE_YEAR      => Query\ParameterType\DateTime\YearType::class,

        ParameterTypeInterface::TYPE_VARCHAR   => Query\ParameterType\String\VarCharType::class,
        ParameterTypeInterface::TYPE_CHAR      => Query\ParameterType\String\CharType::class,
        ParameterTypeInterface::TYPE_BINARY    => Query\ParameterType\String\BinaryType::class,
        ParameterTypeInterface::TYPE_VARBINARY => Query\ParameterType\String\VarBinaryType::class,

        ParameterTypeInterface::TYPE_ENUM      => Query\ParameterType\EnumType::class,

        ParameterTypeInterface::TYPE_RAW         => Query\ParameterType\RawType::class,
        ParameterTypeInterface::TYPE_VALUE_BASED => Query\ParameterType\ValueBasedType::class,
    ];

    /** @var ParameterTypeInterface[] Массив инстацированных типов параметров */
    private $typesInstances;

    /**
     * @param ConnectionInterface $connection Инстанс соединения с базой данных
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->typesInstances = [];
    }

    /**
     * Возвращает соединение с БД текущего парсера запросов
     * @return ConnectionInterface Инстанс соединения с базой данных
     */
    public function getDatabaseConnection() : ConnectionInterface
    {
        return $this->connection;
    }

    /** @inheritdoc */
    public function parseQuery(string $sqlQuery) : QueryInterface
    {
        return $this->getQueryParser()->parseQuery($sqlQuery);
    }

    /** @inheritdoc */
    public function getTypeByAlias(string $typeAlias) : ParameterTypeInterface
    {
        $value = mb_strtolower($typeAlias, 'utf-8');
        if (!array_key_exists($value, $this->typesAliasesMap)) {
            throw new \InvalidArgumentException('Unknown parameter type "'.$typeAlias.'"');
        }
        return $this->getTypeByCode($this->typesAliasesMap[$value]);
    }

    /** @inheritdoc */
    public function getTypeByCode(int $typeId) : ParameterTypeInterface
    {
        if (!array_key_exists($typeId, $this->typesInstances)) {
            if (!array_key_exists($typeId, $this->typesClassMap)) {
                throw new \InvalidArgumentException('Unknown parameter type id "' . $typeId . '"');
            }
            $className = $this->typesClassMap[$typeId];
            $this->typesInstances[$typeId] = new $className;
        }
        return $this->typesInstances[$typeId];
    }

    /** @inheritdoc */
    public function registerType($class, $aliasOrAliases) : Query\ParameterTypeRegistryInterface
    {
        if (is_object($class)) {
            if (!($class instanceof ParameterTypeInterface)) {
                throw new \InvalidArgumentException();
            }
            $this->typesInstances[$this->maxTypeId] = $class;
            $this->typesClassMap[$this->maxTypeId] = get_class($class);
        } else {
            $this->typesClassMap[$this->maxTypeId] = $class;
        }
        if (is_string($aliasOrAliases)) {
            $this->typesAliasesMap[mb_strtolower($aliasOrAliases, 'utf-8')] = $this->maxTypeId;
        } else {
            foreach ($aliasOrAliases as $alias) {
                $this->typesAliasesMap[mb_strtolower($alias, 'utf-8')] = $this->maxTypeId;
            }
        }
        $this->maxTypeId++;
        return $this;
    }

    /**
     * Создаёт, если он ещё не создан, и возвращает используемый парсер запросов
     * @return QueryParserInterface Парсер запросов
     */
    protected function getQueryParser() : QueryParserInterface
    {
        if ($this->parser === null) {
            $this->parser = new QueryParser\SimpleRegExParser($this);
        }
        return $this->parser;
    }
}