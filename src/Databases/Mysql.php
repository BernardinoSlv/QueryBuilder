<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Databases;

class Mysql implements DatabaseInterface
{
    protected ?string $table = null;
    protected array $where = [];
    protected array $whereBindValues = [];
    protected ?string $limit = null;
    protected ?string $base = "";

    public function __construct()
    {
    }

    public function table(string $table): self
    {
        $this->table = $table;
        if (empty($this->base)) {
            $this->base = "SELECT * FROM `{$table}`";
        }
        return $this;
    }

    public function select(array|string $columns = "*"): DatabaseInterface
    {
        if (is_array($columns)) {
            $columnsWithCrasis = [];

            foreach ($columns as $column) {
                $columnsWithCrasis[] = "`{$column}`";
            }
            $this->base = "SELECT " . implode(", ", $columnsWithCrasis) . " FROM `{$this->table}`";
        } elseif($columns === "*") {
            $this->base = "SELECT * FROM `{$this->table}`";
        } else {
            $this->base = "SELECT `{$columns}` FROM `{$this->table}`";
        }
        return $this;
    }

    public function where(string $column, int|string|null $value, string $operator = "="): DatabaseInterface
    {
        $this->where[] = "`{$column}` {$operator} ?";
        $this->whereBindValues[] = $value;
        return $this;
    }

    public function limit(int $limit, ?int $offset = null): DatabaseInterface
    {
        if ($offset) {
            $this->limit = "LIMIT {$offset}, {$limit}";
        } else {
            $this->limit = "LIMIT {$limit}";
        }
        return $this;
    }

    public function getWhereBindValues(): array
    {
        return $this->whereBindValues;
    }

    public function getSql(): string
    {
        $sql = $this->base;

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        if ($this->limit) {
            $sql .= " {$this->limit}";
        }

        return $sql;
    }

}
