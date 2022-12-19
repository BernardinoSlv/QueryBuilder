<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Builder;

use BernardinoSlv\QueryBuilder\Databases\DatabaseInterface;

class QueryBuilder implements QueryBuilderInterface
{
    protected ?DatabaseInterface $database = null;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function first(): QueryBuilderInterface
    {
        $this->database->limit(1);
        return $this;
    }

    public function find(int $id): QueryBuilderInterface
    {
        $this->database->where("id", $id)
            ->limit(1);
        return $this;
    }

    public function table(string $table): QueryBuilderInterface
    {
        $this->database->table($table);
        return $this;
    }

    public function select(array|string $columns = "*"): QueryBuilderInterface
    {
        $this->database->select($columns);
        return $this;
    }

    public function where(string $column, int|string|null $value, string $operator = "="): QueryBuilderInterface
    {
        $this->database->where($column, $value, $operator);
        return $this;
    }

    public function limit(int $limit, ?int $offset = null): QueryBuilderInterface
    {
        $this->database->limit($limit, $offset);
        return $this;
    }

    public function getSql(): string
    {
        return $this->database->getSql();
    }
}
