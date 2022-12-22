<?php

namespace BernardinoSlv\QueryBuilder;

use BernardinoSlv\QueryBuilder\Builder\QueryBuilder;
use BernardinoSlv\QueryBuilder\Databases\Mysql;
use BernardinoSlv\QueryBuilder\Singleton\DbSingleton;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    private ?Model $model = null;

    protected function setUp(): void
    {
        DbSingleton::configure("mysql:dbname=curso_laravel_project;host=localhost", "root");
        $this->model = new Model(new Mysql);
        $this->model->setDb(DbSingleton::getInstance());
    }

    public function testCheckInstance()
    {
        $this->assertInstanceOf(QueryBuilder::class, $this->model);
    }

    public function testGetMethod()
    {
        $this->assertIsArray($this->model->table("user")->get());
    }

    public function testUsingWhereMethod()
    {
        $users = $this->model->table("user")
            ->where("name", "Bernardino")
            ->get();

        $this->assertIsArray($users);
        $this->assertEquals("bernardino@gmail.com", $users[0]["email"]);
    }

    public function testGetFirstMethod()
    {
        $user = $this->model->table("user")->getFirst();

        $this->assertIsArray($user);
        $this->assertEquals("Bernardino", $user["name"]);
    }

    public function testGetFirstMethodUsingWhereMethod()
    {
        $user = $this->model->table("user")
            ->where("name", "testador")
            ->getFirst();

        $this->assertIsArray($user);
        $this->assertEquals("testador@gmail.com", $user["email"]);
    }

    public function testGetMethodUsingLimitMethod()
    {
        $posts = $this->model->table("post")
            ->limit(10)
            ->get();

        $this->assertIsArray($posts);
        $this->assertEquals(10, count($posts));
    }
}
