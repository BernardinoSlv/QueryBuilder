<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder;

use BernardinoSlv\QueryBuilder\Builder\QueryBuilder;
use BernardinoSlv\QueryBuilder\Databases\DatabaseInterface;

class Model extends QueryBuilder
{
    protected ?Db $db = null;
    protected ?string $table = null;

    public function __construct(DatabaseInterface $database)
    {
        parent::__construct($database);

        if (!$this->table) {
            $this->table = ResolverName::resolver($this::class);
        }

        $this->database->table($this->table);
    }

    public function setDb(Db $db)
    {
        $this->db = $db;
    }

    public function get(): array
    {
        $smtp =  $this->db->getPdo()->prepare($this->getSql());
        $smtp->execute($this->database->getWhereBindValues());
        return $smtp->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFirst(): ?array
    {
        $smtp =  $this->db->getPdo()->prepare($this->first()->getSql());
        $smtp->execute($this->database->getWhereBindValues());
        return $smtp->fetch(\PDO::FETCH_ASSOC);
    }
}
