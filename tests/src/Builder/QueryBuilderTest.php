<?php

namespace BernardinoSlv\QueryBuilder\Builder;

use BernardinoSlv\QueryBuilder\Databases\Mysql;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private ?QueryBuilderInterface $queryBuilder = null;

    protected function setUp(): void
    {
        $mysql = new Mysql;
        $this->queryBuilder = new QueryBuilder($mysql);
    }

    public function testFirstMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->first();

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT * FROM `users` LIMIT 1", $queryBuilder->getSql());
    }

    public function testFindMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->find(10);

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT * FROM `users` WHERE `id` = ? LIMIT 1", $queryBuilder->getSql());
    }

    public function testTableMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users");

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT * FROM `users`", $queryBuilder->getSql());
    }

    public function testSelectMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->select("name");

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT `name` FROM `users`", $queryBuilder->getSql());
    }

    public function testWhereMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->where("name", "Bernardino");

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT * FROM `users` WHERE `name` = ?", $queryBuilder->getSql());
    }

    public function testLimitMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->limit(1);

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT * FROM `users` LIMIT 1", $queryBuilder->getSql());
    }

    public function testFirstMethodAndSelectMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->select("name")
            ->first();

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT `name` FROM `users` LIMIT 1", $queryBuilder->getSql());
    }

    public function testFirstMethodAndSelectMethodPassingarrayParam()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->select(["name", "email", "password"])
            ->first();

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT `name`, `email`, `password` FROM `users` LIMIT 1", $queryBuilder->getSql());
    }

    public function testFirstMethodAndSelectAndWhereMethod()
    {
        $queryBuilder = $this->queryBuilder->table("users")
            ->select(["name", "email"])
            ->where("name", "Bernardino")
            ->where("year", 21)
            ->first();

        $this->assertInstanceOf(QueryBuilderInterface::class, $queryBuilder);
        $this->assertEquals("SELECT `name`, `email` FROM `users` WHERE `name` = ? AND `year` = ? LIMIT 1", $queryBuilder->getSql());
    }
}
