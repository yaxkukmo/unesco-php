<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as Capsule;

abstract class DatabaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        $this->tearDownDatabase();
        parent::tearDown();
    }

    protected function setUpDatabase(): void
    {
        // Run migrations
        $this->runMigrations();
    }

    protected function tearDownDatabase(): void
    {
        // Drop all tables
        Capsule::schema()->dropAllTables();
    }

    protected function runMigrations(): void
    {
        $migrationsPath = __DIR__ . '/../src/Migrations';
        $migrations = glob($migrationsPath . '/*.php');
        sort($migrations);

        foreach ($migrations as $migration) {
            require_once $migration;

            $migrationName = basename($migration);
            $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationName);
            $className = str_replace('.php', '', $className);
            $className = str_replace('_', '', ucwords($className, '_'));
            $fullClassName = "App\\Migrations\\{$className}";

            if (class_exists($fullClassName)) {
                $instance = new $fullClassName();
                $instance->up();
            }
        }
    }

    protected function seed(string $table, array $data): void
    {
        Capsule::table($table)->insert($data);
    }
}
