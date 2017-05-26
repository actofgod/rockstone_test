<?php

namespace RockstoneTest\Database\Query;

interface ParameterInterface
{
    /**
     * Возвращает имя параметра для подстановки в SQL запрос
     * @return string Имя парамтера
     */
    function getName() : string;

    /**
     * Возвращает тип парамтера
     * @return ParameterTypeInterface Тип парамтера
     */
    function getType() : ParameterTypeInterface;

    /**
     * Устанавливает значение текущего параметра запроса
     * @param mixed $value Значение парамтера для подстановки в SQL запрос
     * @return ParameterInterface Инстанс текущего объекта
     * @throws \InvalidArgumentException Генерируется если параметр невалиден
     */
    function setValue($value) : ParameterInterface;

    /**
     * Проверяет, было ли установлено значение парамтера до этого
     * @return bool True если значение было установлено, false если нет
     */
    function isValueSet() : bool;

    /**
     * Возвращает значение, установленное до этого методом setValue
     * @param bool $escaped Вернуть ли эскейпированное значение
     * @return mixed Значение пареметра для подстановки в SQL запрос
     */
    function getValue($escaped = true);

    /**
     * Сбрасывает установленное до этого значение
     * @return ParameterInterface Инстанс текущего объекта
     */
    function resetValue() : ParameterInterface;
}