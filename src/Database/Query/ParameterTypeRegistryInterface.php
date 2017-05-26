<?php

namespace RockstoneTest\Database\Query;

interface ParameterTypeRegistryInterface
{
    /**
     * Возвращает объект типа значения по алиасу типа
     * @param string $typeAlias Имя типа в виде строки
     * @return ParameterTypeInterface Объект типа значени
     * @throws \InvalidArgumentException Генерируется если не существует типа с указанным именем
     */
    function getTypeByAlias(string $typeAlias) : ParameterTypeInterface;

    /**
     * Возвращает объект типа параметра по коду типа
     * @param int $typeId Код типа параметра, одна из констант ParameterTypeInterface::TYPE_XXX
     * @return ParameterTypeInterface Объект типа значени
     * @throws \InvalidArgumentException Генерируется если объект типа с указанным кодом не зарегестрирован
     */
    function getTypeByCode(int $typeId) : ParameterTypeInterface;

    /**
     * Добавляет в реестр типов данных новый тип
     * @param string|ParameterTypeInterface $class Объект регестрируемого типа или его имя класса
     * @param string|string[] $aliasOrAliases Алиас или массив алиасов для регестрируемого типа
     * @return ParameterTypeRegistryInterface Инстанс текущего реестра
     */
    function registerType($class, $aliasOrAliases) : ParameterTypeRegistryInterface;
}