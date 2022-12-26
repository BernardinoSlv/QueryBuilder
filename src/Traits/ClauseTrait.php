<?php

namespace BernardinoSlv\QueryBuilder\Traits;

trait ClauseTrait
{
    /**
     * Armazena as clausulas usadas na consulta atual
     *
     * @var array
     */
    protected array $clauses = [];

    /**
     * Adiciona a cláusula na propriedade @var $clauses
     *
     * @param string $clause
     * @return void
     */
    protected function addClause(string $clause): void
    {
        $this->clauses[] = $clause;
    }

    /**
     * Checa se a query atual está usando as cláusulas @param $clauses
     *
     * @param string ...$clauses
     * @return boolean
     */
    protected function haveClauses(string ...$clauses): bool
    {
        foreach ($clauses as $clause) {
            if (in_array($clause, $this->clauses) || in_array(strtolower($clause), $this->clauses)) return true;
        }
        return false;
    }
}
