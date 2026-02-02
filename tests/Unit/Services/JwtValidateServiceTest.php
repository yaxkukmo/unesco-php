<?php

namespace Tests\Unit\Services;

use App\Services\JwtValidateService;
use App\Services\JwtGenerateService;
use Tests\TestCase;
use Firebase\JWT\JWT;

class JwtValidateServiceTest extends TestCase
{
    public function test_invoke_validates_correct_token()
    {
        // Arrange
        $email = 'test@example.com';
        $generateService = new JwtGenerateService();
        $validateService = new JwtValidateService();
        $token = $generateService->handle($email);

        // Act
        $result = $validateService->handle($token);

        // Assert
        $this->assertNotNull($result);
        $this->assertIsObject($result);
        $this->assertEquals($email, $result->email);
        $this->assertEquals('unesco-api', $result->iss);
    }

    public function test_invoke_rejects_invalid_token()
    {
        // Arrange
        $validateService = new JwtValidateService();
        $invalidToken = 'invalid.token.here';

        // Act
        $result = $validateService->handle($invalidToken);

        // Assert
        $this->assertNull($result);
    }

    public function test_invoke_rejects_token_with_wrong_signature()
    {
        // Arrange
        $validateService = new JwtValidateService();

        // Create token with different secret (must be at least 256 bits / 32 bytes for HS256)
        $payload = [
            'iss' => 'unesco-api',
            'email' => 'test@example.com',
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $wrongToken = JWT::encode($payload, 'wrong-secret-key-must-be-at-least-32-chars-long-for-hs256', 'HS256');

        // Act
        $result = $validateService->handle($wrongToken);

        // Assert
        $this->assertNull($result);
    }

    public function test_invoke_rejects_expired_token()
    {
        // Arrange
        $validateService = new JwtValidateService();

        // Create expired token
        $payload = [
            'iss' => 'unesco-api',
            'email' => 'test@example.com',
            'iat' => time() - 7200,
            'exp' => time() - 3600  // Expired 1 hour ago
        ];
        $expiredToken = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        // Act
        $result = $validateService->handle($expiredToken);

        // Assert
        $this->assertNull($result);
    }
}
