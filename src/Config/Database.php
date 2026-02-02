<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    private static ?Capsule $capsule = null;

    public static function init(): void
    {
        if (self::$capsule !== null) {
            return;
        }

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
            'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port'      => $_ENV['DB_PORT'] ?? '3306',
            'database'  => $_ENV['DB_DATABASE'] ?? 'unesco_heritage',
            'username'  => $_ENV['DB_USERNAME'] ?? 'root',
            'password'  => $_ENV['DB_PASSWORD'] ?? '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$capsule = $capsule;
    }

    public static function getCapsule(): Capsule
    {
        if (self::$capsule === null) {
            self::init();
        }

        return self::$capsule;
    }
}
