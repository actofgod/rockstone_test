
# rockstone_test

Тестовое задание для Rockstone.

Напишите класс, реализующий собственный функционал плейсхолдеров для запросов к БД MySQL.

#### Мои замечания

Сначала хотел реализовать два парсера, один, который готов, на основе регулярных выражений, а второй на основе
конечного автомата, но до него руки не дошли.

Типы параметров можно было реализовывать не каждый отдельным классом, а разбить на более крупные группы: целые
числа, числа с плавающей точкой и т.п., но эта мысль мне пришла поздно, переписывать не стал.

Вообще, на мой взгляд, лучше использовать нативные плейсхолдеры PDO.

#### Запуск и тестирование

Для работы требуется версия php 7.0 и выше.

Для начала нужно подтянуть зависимости
```bash
composer update
```

Если хочется запустить тесты, а phpunit глобально не стоит, то нужно сначала его добавить в зависимости
и сделать апдейт
```bash
composer require --dev phpunit/phpunit ^6.1
composer update
```

Тогда можно и тесты запустить:
```bash
php vendor/bin/phpunit
```

#### Как использовать

В парсер запросов передайтся текст запроса, в запросе можно использовать плейсхолдеры в виде { name : type }
```SQL
SELECT * FROM table WHERE column = {column:smallint}
```

Типы mysql реализованы не все, а только эти:
* tinyint
* smallint
* mediumint
* integer
* bigint
* float
* double
* datetime
* date
* timestamp
* year
* time
* varchar
* char
* binary
* varbinary
* enum
* raw - вставляет значение в запрос как есть
* и тип, определяющийся на основе типа переданного параметра

При создании запроса в плейсхолдере тип не обязателен, если его не передать, то эскейпирование будет произведено в
зависимости от того, какой аргумент был передан при установке значения.

В запросе
```SQL
SELECT * FROM table WHERE column = { column }
```
подставляться в запрос будет значение в зависимости от его типа, если установить значение 1, то в запрос будет
подставлена еденица без кавычек, а если строка "1", в запросе будет '1'.

Так же QueryParser реализует интерфейс ParameterTypeRegistryInterface, поэтому можно реализовать свой тип данных для
подставновки в запрос, реализовав интерфейс ParameterTypeInterface, а затем зарегестроровать его, как-то так:
```php

$queryParser->registerType('ClassName', 't');
// теперь можно использовать тип t
$query = $queryParser->parseQuery('SELECT * FROM { table : t } LIMIT 0, 10');
$result = $db->execute($query->bindValue('table', 'table_name'));
```

#### Примеры использования:

Для начала нужно парсер создать, ему нужено соединение с базой данных для эскейпирования строк:
```php

use \RockstoneTest\Database\FakeDatabaseConnection;
use \RockstoneTest\Database\QueryParser;

$connection = new FakeDatabaseConnection();
$queryParser = new QueryParser($connection);
```

А затем можно создавать запросы и как-то с ними работать:
```php

$query = $queryParser->parseQuery(
    'SELECT * FROM users WHERE id = { id } AND tmp = { raw : raw } AND e = { tmp : enum_active }'
);
$sql = $query->getSqlQueryForExecute([
    'id'  => 5,
    'raw' => "'%test_test%'",
    'tmp' => 'N'
]);
echo $sql . PHP_EOL;
// выведет "SELECT * FROM users WHERE id = 5 AND tmp = '%test_test%' AND e = 'N'"
```

Так как метод __toString возвращает текст запроса, можно делать так:
```php

$query = $queryParser->parseQuery(
    'SELECT * FROM users WHERE id = { id } AND tmp = { raw : raw } AND e = { tmp : enum_active }'
);
$query->bindValues([
    'id'  => 5,
    'raw' => "'%test_test%'",
    'tmp' => 'N'
]);
echo $query . PHP_EOL;
// выведет "SELECT * FROM users WHERE id = 5 AND tmp = '%test_test%' AND e = 'N'"
```

Для пересеслений есть отдельный тип, с ним можно вытворять такое:
```php

// если в какой-то таблице есть колонка типа ENUM('Y','N')
$enum = new \RockstoneTest\Database\Query\ParameterType\EnumType();
$queryParser->registerType($enum->setOptions('Y', 'N'), 'enum_active');

$query = $queryParser->parseQuery('SELECT * FROM users WHERE active = { tmp : enum_active }');
$query->bindValue('tmp', 'N');
echo $query . PHP_EOL;
// выведет "SELECT * FROM users WHERE active = 'N'"

// а тут уже возникнет исключение:
$query->bindValue('tmp', 'no');
```
