# Query builder

Mini query builder desenvolvido para praticar a implementação de design patterns como **[Strategy](https://refactoring.guru/design-patterns/strategy)** e **[Builder](https://refactoring.guru/pt-br/design-patterns/builder)** (<font color="red">Não teve necessidade de implementar o Director</font>).

> Uso básico do mysql (BernardinoSlv\QueryBuilder\Databases\Mysql)
```
require __DIR__ . "/vendor/autoload.php";

use BernardinoSlv\QueryBuilder\Databases\Mysql;


$mysql = new Mysql;

$sql = $mysql->table("users")
    ->where("id", 10)
    ->getSql(); // Obtem a query

echo $sql;

// SELECT * FROM `users` WHERE `id` = ?
```
:bulb: É uma **[fluent interface](https://designpatternsphp.readthedocs.io/en/latest/Structural/FluentInterface/README.html)**, assim suportando métodos encadeados

:memo: **Métodos suportados atualmente**
- table
- select
- where
- limit
- getSql ( retorna uma string contendo o resultado/query)

<br />

> Uso básico da QueryBuilder BernardinoSlv\QueryBuilder\Databases\Mysql
```
<?php

require __DIR__ . "/vendor/autoload.php";

use BernardinoSlv\QueryBuilder\Builder\QueryBuilder;
use BernardinoSlv\QueryBuilder\Databases\Mysql;


$mysql = new Mysql;

$queryBuilder = new QueryBuilder($mysql);

$sql = $queryBuilder->table("users")
    ->find(1)
    ->getSql();

echo $sql;
// SELECT * FROM `users` WHERE `id` = ? LIMIT 1
```

:bulb: Também é uma fluent interface

:memo: **Métodos suportados atualmente**
- table
- select
- where
- limit
- find
- first
- getSql ( retorna uma string contendo o resultado/query)

<br/>

> Outro exemplo
```
<?php

require __DIR__ . "/vendor/autoload.php";

use BernardinoSlv\QueryBuilder\Builder\QueryBuilder;
use BernardinoSlv\QueryBuilder\Databases\Mysql;


$mysql = new Mysql;

$queryBuilder = new QueryBuilder($mysql);

$sql = $queryBuilder->table("users")
    ->select(["name", "email", "password"])
    ->where("name", "Bernardino")
    ->where("last_name", "Silva")
    ->first()
    ->getSql();

echo $sql;
// SELECT `name`, `email`, `password` FROM `users` WHERE `name` = ? AND `last_name` = ? LIMIT 1
```

:warning: Foi construida respeitando os principios **[SOLID](https://pt.wikipedia.org/wiki/SOLID)** pensando com carinho no **[Open-Closed Principle](https://www.digitalocean.com/community/conceptual-articles/s-o-l-i-d-the-first-five-principles-of-object-oriented-design)** Então fica simples expandir, adicionando novos métodos e criando novas classes (*BernardinoSlv\QueryBuilder\Databases\DatabaseInterface* quanto BernardinoSlv\QueryBuilder\Builder\QueryBuilderInterface). **Fique a vontado para contribuir, toda contribuição será bem vinda**
