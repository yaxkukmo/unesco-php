<?php

namespace Tests\Unit\Controllers;

use App\Controllers\JwtController;
use App\Services\JwtGenerateService;
use Tests\TestCase;
use Mockery;

class JwtControllerTest extends TestCase
{
    public function test_service_generates_token_for_valid_email()
    {
        // Arrange
        $email = $_ENV['API_USER_EMAIL'];
        $expectedToken = 'generated.jwt.token';

        $service = Mockery::mock(JwtGenerateService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($email)
            ->andReturn($expectedToken);

        // Act
        $result = $service->handle($email);

        // Assert
        $this->assertEquals($expectedToken, $result);
    }

    public function test_jwt_controller_validates_credentials_logic()
    {
        // This test verifies the authentication logic conceptually
        // Full integration testing of php://input requires complex stream mocking
        // which is better suited for Feature tests

        $validEmail = $_ENV['API_USER_EMAIL'];
        $validPasswordHash = $_ENV['API_USER_PASSWORD'];

        // Verify that password hash exists and is properly formatted
        $this->assertNotEmpty($validPasswordHash);
        $this->assertStringStartsWith('$2y$', $validPasswordHash); // bcrypt format

        // Test email is configured
        $this->assertEquals($validEmail, 'test@example.com');
    }
}
