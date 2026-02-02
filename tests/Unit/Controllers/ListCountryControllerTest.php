<?php

namespace Tests\Unit\Controllers;

use App\Controllers\ListCountryController;
use App\Services\ListCountryService;
use Tests\TestCase;
use Mockery;

class ListCountryControllerTest extends TestCase
{
    public function test_invoke_returns_countries_data()
    {
        // Arrange
        $countries = [
            ['id' => 1, 'name' => 'Poland'],
            ['id' => 2, 'name' => 'France'],
        ];

        $result = new \App\Results\ListCountryResult($countries);

        $service = Mockery::mock(ListCountryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->andReturn($result);

        $controller = new ListCountryController($service);

        // Act
        ob_start();
        $controller();
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals('Poland', $response['data'][0]['name']);
    }

    public function test_invoke_returns_empty_array_when_no_countries()
    {
        // Arrange
        $result = new \App\Results\ListCountryResult([]);

        $service = Mockery::mock(ListCountryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->andReturn($result);

        $controller = new ListCountryController($service);

        // Act
        ob_start();
        $controller();
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertEmpty($response['data']);
    }
}
