<?php

namespace RockstoneTest\Database\Query;

interface ParameterTypeInterface
{
    const TYPE_NULL = 0;

    const TYPE_BOOL = 1;

    const TYPE_TINYINT = 2;
    const TYPE_SMALLINT = 3;
    const TYPE_MEDIUMINT = 4;
    const TYPE_INTEGER = 5;
    const TYPE_BIGINT = 6;

    const TYPE_DECIMAL = 7;

    const TYPE_FLOAT = 8;
    const TYPE_DOUBLE = 9;

    const TYPE_DATE = 20;
    const TYPE_DATETIME = 21;
    const TYPE_TIMESTAMP = 22;
    const TYPE_TIME = 23;
    const TYPE_YEAR = 24;

    const TYPE_CHAR = 30;
    const TYPE_VARCHAR = 31;
    const TYPE_BINARY = 32;
    const TYPE_VARBINARY = 33;
    const TYPE_TINYBLOB = 34;
    const TYPE_TINYTEXT = 35;
    const TYPE_TEXT = 36;
    const TYPE_BLOB = 37;
    const TYPE_MEDIUMTEXT = 38;
    const TYPE_MEDIUMBLOB = 39;
    const TYPE_LONGTEXT = 40;
    const TYPE_LONGBLOB = 41;

    const TYPE_ENUM = 50;

    const TYPE_RAW = 98;
    const TYPE_VALUE_BASED = 99;

    /**
     * Может ли класс для текущего типа валидировать значение
     * @return bool True если объект может валидировать значения, false если нет
     */
    function canValidate() : bool;

    /**
     * Валидирает переданное значение
     * @param mixed $value Проверяемое значение
     * @return bool True если значение валидно, false если нет
     */
    function validate($value) : bool;

    /**
     * Эскейпирует занчение для вставки напрямую в строку запроса
     * @param mixed $value Эскейпируемое значение
     * @return mixed Значение для вставки в строку запроса
     */
    function escapeValue($value);

    /**
     * Возвращает константу \PDO::PARAM_XXX для текущего типа данных
     * @param mixed $value Значение для определения типа, используется только в классе, определяющем тип по значению
     * @return int Константа для передачи в bindValue библиотеки PDO
     */
    function getPdoTypeCode($value) : int;

    /**
     * Требуется ли эскейпирование значения самой базой данных
     * @return bool True если эскейпирование требуется, false если нет
     */
    function databaseEscapingNeeded() : bool;
}
