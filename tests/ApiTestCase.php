<?php

namespace Tests;

use App\Services\JwtGenerateService;

abstract class ApiTestCase extends DatabaseTestCase
{
    protected function generateToken(string $email = 'test@example.com'): string
    {
        $service = new JwtGenerateService();
        return $service($email);
    }

    protected function captureJsonResponse(callable $callback): array
    {
        ob_start();
        $callback();
        $output = ob_get_clean();

        return json_decode($output, true) ?? [];
    }

    protected function mockAuthHeader(string $token): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer {$token}";
    }

    protected function clearAuthHeader(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
    }
}
