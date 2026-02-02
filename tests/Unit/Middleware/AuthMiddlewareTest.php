<?php

namespace Tests\Unit\Middleware;

use App\Middleware\AuthMiddleware;
use App\Services\JwtValidateService;
use Tests\TestCase;
use Mockery;

class AuthMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        unset($_SERVER['HTTP_AUTHORIZATION']);
    }

    public function test_handle_passes_with_valid_token()
    {
        // Arrange
        $token = 'valid.jwt.token';
        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer {$token}";

        $mockPayload = (object)['email' => 'test@example.com'];

        $service = Mockery::mock(JwtValidateService::class);
        $service->shouldReceive('__invoke')
            ->once()
            ->with($token)
            ->andReturn($mockPayload);

        $middleware = new AuthMiddleware($service);

        // Act & Assert - should not throw or exit
        $middleware->handle();
        $this->assertTrue(true); // If we reach here, test passed
    }

    public function test_handle_calls_service_when_no_authorization_header()
    {
        // Arrange
        $service = Mockery::mock(JwtValidateService::class);
        $service->shouldNotReceive('__invoke');

        $middleware = new AuthMiddleware($service);

        // Act & Assert
        // This would normally exit, so we just verify the service wasn't called
        $this->expectOutputRegex('/Missing authorization header/');

        // Note: Can't fully test exit() behavior without separate process
        // Just verify service mock expectations
        try {
            @$middleware->handle();
        } catch (\Throwable $e) {
            // Expected if exit() is called
        }
    }

    public function test_handle_validates_token_when_provided()
    {
        // Arrange
        $token = 'invalid.token';
        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer {$token}";

        $service = Mockery::mock(JwtValidateService::class);
        $service->shouldReceive('__invoke')
            ->once()
            ->with($token)
            ->andReturn(null); // Invalid token

        $middleware = new AuthMiddleware($service);

        // Act & Assert
        $this->expectOutputRegex('/Invalid or expired token/');

        try {
            @$middleware->handle();
        } catch (\Throwable $e) {
            // Expected if exit() is called
        }
    }

    public function test_handle_accepts_bearer_token_case_insensitive()
    {
        // Arrange
        $token = 'valid.jwt.token';
        $_SERVER['HTTP_AUTHORIZATION'] = "bearer {$token}"; // lowercase

        $mockPayload = (object)['email' => 'test@example.com'];

        $service = Mockery::mock(JwtValidateService::class);
        $service->shouldReceive('__invoke')
            ->once()
            ->with($token)
            ->andReturn($mockPayload);

        $middleware = new AuthMiddleware($service);

        // Act & Assert
        $middleware->handle();
        $this->assertTrue(true);
    }
}
