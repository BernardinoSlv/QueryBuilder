<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Builder;

interface QueryBuilderInterface
{
    /**
     * Obtem a linha com o id correspondente
     *
     * @param integer $id
     * @return self
     */
    public function find(int $id): self;

    public function first(): self;

    public function table(string $table): self;

    public function select(array|string $columns = "*"): self;

    public function where(string $column, int|string|null $value, string $operator = "="): self;

    public function limit(int $limit, ?int $offset = null): self;

    public function getSql(): string;
}
