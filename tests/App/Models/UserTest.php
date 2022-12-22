<?php

namespace App\Models;

use BernardinoSlv\QueryBuilder\Databases\Mysql;
use BernardinoSlv\QueryBuilder\Model;
use BernardinoSlv\QueryBuilder\Singleton\DbSingleton;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private ?Model $user = null;

    protected function setUp(): void
    {
        DbSingleton::configure("mysql:dbname=curso_laravel_project;host=localhost", "root");

        $this->user = new User(new Mysql);
        $this->user->setDb(DbSingleton::getInstance());
    }

    public function testGetSqlMethod()
    {
        $query = $this->user->getSql();


        $this->assertEquals("SELECT * FROM `user`", $query);
    }

    public function testGetMethod()
    {
        $data = $this->user->get();

        $this->assertIsArray($data);
        $this->assertEquals("Bernardino", $data[0]["name"]);
    }
}
