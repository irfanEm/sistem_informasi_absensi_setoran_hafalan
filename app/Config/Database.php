<?php

namespace IRFANM\SIASHAF\Config;

use PDO;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConn(string $env = 'test'): \PDO
    {
        if(self::$pdo == null) {
            require_once __DIR__ . '/../../config/db.php';
            $config = getDatabaseConfig();
            self::$pdo = new \PDO(
                $config["database"][$env]["url"],
                $config["database"][$env]["username"],
                $config["database"][$env]["password"],
            );
        }

        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction()
    {
        self::$pdo->rollBack();
    }
}

