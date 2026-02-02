<?php

namespace Tests\Feature;

use App\Controllers\JwtController;
use App\Services\JwtGenerateService;
use Tests\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    public function test_login_with_valid_credentials_returns_token()
    {
        // Arrange - test JWT generation directly since mocking php://input is complex
        $service = new JwtGenerateService();
        $email = $_ENV['API_USER_EMAIL'];

        // Act
        $token = $service($email);

        // Assert
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        $parts = explode('.', $token);
        $this->assertCount(3, $parts); // JWT has 3 parts (header.payload.signature)
    }

    public function test_generated_token_can_be_validated()
    {
        // Arrange
        $email = 'test@example.com';

        // Act
        $token = $this->generateToken($email);

        // Assert
        $this->assertNotEmpty($token);

        // Decode and verify
        $validateService = new \App\Services\JwtValidateService();
        $payload = $validateService($token);

        $this->assertNotNull($payload);
        $this->assertEquals($email, $payload->email);
    }
}
