<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Databases;

interface DatabaseInterface
{
    public function table(string $table): self;

    public function select(array|string $columns = "*"): self;

    public function where(string $column, string|int|null $value, string $operator = "="): self;

    public function limit(int $limit, ?int $offset = null): self;

    public function getSql(): string;
}
