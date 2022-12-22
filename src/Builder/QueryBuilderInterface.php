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
    public function find(int $id): static;

    public function first(): static;

    public function table(string $table): static;

    public function select(array|string $columns = "*"): static;

    public function where(string $column, int|string|null $value, string $operator = "="): static;

    public function limit(int $limit, ?int $offset = null): static;

    public function getSql(): string;
}
