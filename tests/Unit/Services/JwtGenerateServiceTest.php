<?php

namespace Tests\Unit\Services;

use App\Services\JwtGenerateService;
use Tests\TestCase;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtGenerateServiceTest extends TestCase
{
    public function test_invoke_generates_valid_jwt_token()
    {
        // Arrange
        $email = 'test@example.com';
        $service = new JwtGenerateService();

        // Act
        $token = $service->handle($email);

        // Assert
        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        // Decode and verify token
        $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        $this->assertEquals('unesco-api', $decoded->iss);
        $this->assertEquals($email, $decoded->email);
        $this->assertIsInt($decoded->iat);
        $this->assertIsInt($decoded->exp);
        $this->assertGreaterThan($decoded->iat, $decoded->exp);
    }

    public function test_token_contains_correct_expiration()
    {
        // Arrange
        $email = 'test@example.com';
        $service = new JwtGenerateService();
        $beforeTime = time();

        // Act
        $token = $service->handle($email);

        // Assert
        $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        $expectedExpiration = $beforeTime + (int)$_ENV['JWT_EXPIRATION'];

        // Allow 1 second tolerance for execution time
        $this->assertEqualsWithDelta($expectedExpiration, $decoded->exp, 1);
    }
}
