<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load test environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Override with test-specific values
$_ENV['APP_ENV'] = 'testing';
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = ':memory:';
$_ENV['JWT_SECRET'] = 'test-secret-key-for-testing-only-min-32-chars';
$_ENV['JWT_EXPIRATION'] = '3600';
$_ENV['API_USER_EMAIL'] = 'test@example.com';
// Password: "password" hashed with bcrypt
$_ENV['API_USER_PASSWORD'] = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5/dLlIUqvCQum';

// Initialize database for tests
App\Config\Database::init();
