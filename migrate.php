<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

echo "Uruchamianie migracji...\n\n";

$migrationsPath = __DIR__ . '/src/Migrations';
$migrations = glob($migrationsPath . '/*.php');

// Sortuj migracje alfabetycznie (dzięki timestampom w nazwach)
sort($migrations);

foreach ($migrations as $migration) {
    $migrationName = basename($migration);
    echo "Wykonywanie: {$migrationName}...";

    require_once $migration;

    // Nazwa klasy z nazwy pliku (usuń timestamp i .php)
    $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationName);
    $className = str_replace('.php', '', $className);
    $className = str_replace('_', '', ucwords($className, '_'));

    $fullClassName = "App\\Migrations\\{$className}";

    if (class_exists($fullClassName)) {
        $instance = new $fullClassName();
        $instance->up();
        echo " ✓\n";
    } else {
        echo " ✗ (klasa nie znaleziona: {$fullClassName})\n";
    }
}

echo "\nMigracje zakończone!\n";
