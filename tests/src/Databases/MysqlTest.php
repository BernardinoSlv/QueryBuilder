<?php

namespace BernardinoSlv\QueryBuilder\Databases;

use BernardinoSlv\QueryBuilder\Exceptions\MethodNotAllowed;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    private ?DatabaseInterface $mysql = null;

    protected function setUp(): void
    {
        $this->mysql = new Mysql;
    }


    public function testTable()
    {
        $mysql = $this->mysql->table("users");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `users`", $mysql->getSql());
    }

    public function testSelectWithJoker()
    {
        $mysql = $this->mysql->table("clients")
            ->select("*");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `clients`", $mysql->getSql());
    }

    public function testSelectWithEmailColumn()
    {
        $mysql = $this->mysql->table("users")
            ->select("email");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `email` FROM `users`", $mysql->getSql());
    }

    public function testSelectWithArrayColumn()
    {
        $mysql = $this->mysql->table("users")
            ->select(["name", "email", "password"]);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `name`, `email`, `password` FROM `users`", $mysql->getSql());
    }

    public function testWhere()
    {
        $mysql = $this->mysql->table("users")
            ->where("name", "Bernardino");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals('SELECT * FROM `users` WHERE `name` = ?', $mysql->getSql());
    }

    public function testWhereUsingOperatorParameter()
    {
        $mysql = $this->mysql->table("users")
            ->where("name", "Bernardino", "=")
            ->where("year", 18, ">")
            ->where("year", 70, "<");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals('SELECT * FROM `users` WHERE `name` = ? AND `year` > ? AND `year` < ?', $mysql->getSql());
    }

    public function testSelectWhere()
    {
        $mysql = $this->mysql->table("users")
            ->select("name")
            ->where("id", 1);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals('SELECT `name` FROM `users` WHERE `id` = ?', $mysql->getSql());
    }

    public function testSelectWithArrayAndManyWhere()
    {
        $mysql = $this->mysql->table("users")
            ->where("name", "Bernardino")
            ->where("password", "123123")
            ->where("year", 18, ">")
            ->where("height", 181, "<")
            ->select(["name", "email", "password"]);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `name`, `email`, `password` FROM `users` WHERE `name` = ? AND `password` = ? AND `year` > ? AND `height` < ?", $mysql->getSql());
    }

    public function testLimit()
    {
        $mysql = $this->mysql->table("users")
            ->limit(10);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `users` LIMIT 10", $mysql->getSql());
    }

    public function testLimitUsingOffsetParam()
    {
        $mysql = $this->mysql->table("users")
            ->limit(10, 5);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `users` LIMIT 5, 10", $mysql->getSql());
    }

    public function testLimitUsingSelect()
    {
        $mysql = $this->mysql->table("users")
            ->limit(10)
            ->select("name");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `name` FROM `users` LIMIT 10", $mysql->getSql());
    }

    public function testLimitUsingWhere()
    {
        $mysql = $this->mysql->table("users")
            ->limit(10)
            ->where("name", "Bernardino");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `users` WHERE `name` = ? LIMIT 10", $mysql->getSql());
    }

    public function testLimitUsingOffsetParamAndWhere()
    {
        $mysql = $this->mysql->table("users")
            ->limit(10, 20)
            ->where("name", "Bernardino");

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT * FROM `users` WHERE `name` = ? LIMIT 20, 10", $mysql->getSql());
    }

    public function testLimitUsingSelectAndWhere()
    {
        $mysql = $this->mysql->table("users")
            ->select("email")
            ->where("id", 10)
            ->limit(1);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `email` FROM `users` WHERE `id` = ? LIMIT 1", $mysql->getSql());
    }

    public function testLimitWithOffsetParamAndSelectAndWhere()
    {
        $mysql = $this->mysql->table("users")
            ->select("email")
            ->where("id", 10)
            ->limit(1, 20);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `email` FROM `users` WHERE `id` = ? LIMIT 20, 1", $mysql->getSql());
    }

    public function testLimitWithOffsetParamAndSelectAndWhereManyParameter()
    {
        $mysql = $this->mysql->table("users")
            ->select(["name", "email", "year"])
            ->where("is_admin", 1, "!=")
            ->where("email", null, "!=")
            ->where("status", 1)
            ->limit(100, 20);

        $this->assertInstanceOf(DatabaseInterface::class, $mysql);
        $this->assertEquals("SELECT `name`, `email`, `year` FROM `users` WHERE `is_admin` != ? AND `email` != ? AND `status` = ? LIMIT 20, 100", $mysql->getSql());
    }

    public function testCreate()
    {
        $mysql = $this->mysql->table("user")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com",
            ]);

        $this->assertEquals("INSERT INTO `user` (`name`, `email`) VALUE (?, ?)", $mysql->getSql());
    }

    public function testCreateExceptionMethodNotAllowedUsingWhereMethod()
    {
        $this->expectException(MethodNotAllowed::class);

        $this->mysql->table("user")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com",
            ])
            ->where("name", "Bernardino");
    }

    public function testCreateExceptionMethodNotAllowedMessageUsingWhereMethod()
    {
        $this->expectExceptionMessage("Método where não é permitido em queries que usam cláusula CREATE");
        $this->mysql->table("user")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com",
            ])
            ->where("name", "Bernardino");
    }

    public function testCreateExceptionMethodNotAllowedUsingLimitMethod()
    {
        $this->expectException(MethodNotAllowed::class);

        $this->mysql->table("user")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com"
            ])
            ->limit(10);
    }

    public function testCreateExceptionMethodNotAllowedMessageUsingLimitMethod()
    {
        $this->expectExceptionMessage("Método limit não é permitido em queries que usam cláusula CREATE");
        $this->mysql->table("user")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com",
            ])
            ->limit(10);
    }

    public function testCreateExceptionMethodNotAllowedUsingSelectMethod()
    {
        $this->expectException(MethodNotAllowed::class);

        $this->mysql->table("user")
            ->select("name", "email")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com"
            ]);
    }

    public function testCreateExceptionMethodNotAllowedMessageUsingSelectMethod()
    {
        $this->expectExceptionMessage("Método where não é permitido em queries que usam cláusulas SELECT, WHERE ou LIMIT");

        $this->mysql->table("user")
            ->select("name", "email")
            ->create([
                "name" => "Bernardino",
                "email" => "bernardinoosilvaa@gmail.com"
            ]);
    }
}
