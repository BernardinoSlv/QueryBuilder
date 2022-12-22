<?php

namespace BernardinoSlv\QueryBuilder;

use PDO;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    private ?Db $db = null;

    protected function setUp(): void
    {
        $pdo = new PDO("mysql:dbname=curso_php_mod_20;host=localhost", "root");
        $this->db = new Db($pdo);
    }

    public function testInstanceOfUsingGetPdoMethod()
    {
        $this->assertInstanceOf(PDO::class, $this->db->getPdo());
    }
}
