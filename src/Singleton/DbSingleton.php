<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder\Singleton;

use BernardinoSlv\QueryBuilder\Db;
use PDO;

class DbSingleton
{
    private static ?Db $instance = null;
    private static array $configure = [];

    protected function __construct() {}
    public function __wakeup() {}
    protected function __clone() {}

    public static function isConfigured(): bool
    {
        return !empty(self::$configure["dns"]);
    }


    public static function configure(string $dns, string $user, ?string $pass = null, array $options = [])
    {
        self::$configure["dns"] = $dns;
        self::$configure["user"] = $user;
        self::$configure["password"] = $pass;
        self::$configure["options"] = $options;
    }

    public static function getInstance(): Db
    {
        if (empty(self::$instance)) {
            $pdo = new PDO(
                self::$configure["dns"],
                self::$configure["user"],
                self::$configure["password"],
                self::$configure["options"]
            );

            self::$instance = new Db($pdo);
        }

        return self::$instance;
    }
}
