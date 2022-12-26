<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Databases;

use BernardinoSlv\QueryBuilder\Exceptions\MethodNotAllowed;
use BernardinoSlv\QueryBuilder\Traits\ClauseTrait;

class Mysql implements DatabaseInterface
{
    use ClauseTrait;

    /**
     * Tabela que será feita a consulta
     *
     * @var string|null
     */
    protected ?string $table = null;

    /**
     * Todas as colunas do where
     *
     * @var array
     */
    protected array $where = [];

    /**
     * Valores de cada where
     *
     * @var array
     */
    protected array $whereBindValues = [];

    /**
     * Valores dos create
     *
     * @var array
     */
    protected array $createBindValues = [];

    /**
     * Texto do limit
     *
     * @var string|null
     */
    protected ?string $limit = null;

    /**
     * Base da query
     *
     * @var string|null
     */
    protected ?string $base = "";

    public function table(string $table): self
    {
        $this->table = $table;
        if (empty($this->base)) {
            $this->base = "SELECT * FROM `{$table}`";
        }
        return $this;
    }

    public function create(array $data): self
    {
        $this->addClause("CREATE");

        if ($this->haveClauses("SELECT", "WHERE", "LIMIT")) {
            throw new MethodNotAllowed("Método where não é permitido em queries que usam cláusulas SELECT, WHERE ou LIMIT");
        }

        $keys = [];
        foreach (array_keys($data) as $key) {
            $keys[] = "`{$key}`";
        }
        $values = array_values($data);
        $interrogations = explode("-", str_repeat("?-", count($keys)));
        array_pop($interrogations);

        $this->createBindValues = $values;


        $this->base = "INSERT INTO `{$this->table}` (" . implode(", ", $keys) . ") VALUE (" . implode(", ", $interrogations) . ")";
        return $this;
    }

    /**
     * Trata da cláusula select
     *
     * @param string $columns
     * @return DatabaseInterface
     */
    public function select(array|string $columns = "*"): DatabaseInterface
    {
        $this->addClause("SELECT");

        if ($this->haveClauses("CREATE", "DELETE")) {
            throw new MethodNotAllowed("Método select não é permitido em queries que usam cláusulas CREATE OU DELETE");
        }
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

    /**
     * Trata da cláusula where
     *
     * @param string $column
     * @param integer|string|null $value
     * @param string $operator
     * @return DatabaseInterface
     */
    public function where(string $column, int|string|null $value, string $operator = "="): DatabaseInterface
    {
        $this->addClause("WHERE");

        if ($this->haveClauses("CREATE")) {
            throw new MethodNotAllowed("Método where não é permitido em queries que usam cláusula CREATE");
        }
        $this->where[] = "`{$column}` {$operator} ?";
        $this->whereBindValues[] = $value;
        return $this;
    }

    /**
     * Trata da cláusula limit
     *
     * @param integer $limit
     * @param integer|null $offset
     * @return DatabaseInterface
     */
    public function limit(int $limit, ?int $offset = null): DatabaseInterface
    {
        $this->addClause("LIMIT");

        if ($this->haveClauses("CREATE")) {
            throw new MethodNotAllowed("Método limit não é permitido em queries que usam cláusula CREATE");
        }

        $this->limit = $offset ? "LIMIT {$offset}, {$limit}" : "LIMIT {$limit}";
        return $this;
    }

    /**
     * Retorna os valores que serão ligados (bind) ao where
     *
     * @return array
     */
    public function getWhereBindValues(): array
    {
        return $this->whereBindValues;
    }

    /**
     * Obtem o sql da query atual
     *
     * @return string
     */
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
