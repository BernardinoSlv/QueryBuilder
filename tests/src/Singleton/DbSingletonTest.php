<?php

namespace BernardinoSlv\QueryBuilder\Singleton;

use BernardinoSlv\QueryBuilder\Db;
use PHPUnit\Framework\TestCase;

class DbSingletonTest extends TestCase
{
    protected function setUp(): void
    {
        if (DbSingleton::isConfigured()) {
            echo "Já foi configurado\n";
        } else {
            echo "Ainda não foi configurado\n";
        }

        DbSingleton::configure(
            "mysql:dbname=curso_php_mod_20;host=localhost",
            "root",
        );
    }


    public function testInstanceOf()
    {
        $db = DbSingleton::getInstance();

        $this->assertInstanceOf(Db::class, $db);
    }

    public function testSingleInstance()
    {
        $db = DbSingleton::getInstance();
        $db2 = DbSingleton::getInstance();

        $this->assertInstanceOf(Db::class, $db);
        $this->assertInstanceOf(Db::class, $db2);
        $this->assertEquals($db, $db2);
    }
}
