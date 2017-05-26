<?php

namespace RockstoneTest\Database;

interface QueryInterface
{
    /**
     * Устанавливает значение указанного параметра
     * @param string|int $parameter Имя параметра
     * @param mixed $value Устанавливаемое значение
     * @return QueryInterface Инстанс текущего объекта
     */
    function bindValue($parameter, $value) : QueryInterface;

    /**
     * Устанавливает значения указанных в массиве параметров
     * @param array $parameters Массив устанавливаемых значений
     * @return QueryInterface Инстанс текущего объекта
     */
    function bindValues(array $parameters) : QueryInterface;

    /**
     * Возвращает SQL запрос в виде строки для передачи его в метод \PDO::prepare()
     * @return string Запрос в виде стороки для создания подготовленного запроса
     */
    function getSqlQueryForPrepare() : string;

    /**
     * Возвращает SQL запрос в виде строки
     * @param array $parameters Параметры для установки в текст запроса
     * @return string Текст SQL запроса готовый для передачи базе данных
     */
    function getSqlQueryForExecute(array $parameters = []) : string;

    /**
     * Сбрасывает все установленные до этого параметры запроса
     * @return QueryInterface Инстанс текущего объекта запроса
     */
    function reset() : QueryInterface;

    /**
     * Преобразует запрос в SQL строку, если какие-то параметры не установлены до этого, выбрасывает исключение
     * @return string SQL запрос в виде строки
     */
    public function __toString();
}
