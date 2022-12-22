<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder;

use PDO;

class Db
{
    protected ?PDO $pdo = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
