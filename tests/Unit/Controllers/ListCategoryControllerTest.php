<?php

namespace Tests\Unit\Controllers;

use App\Controllers\ListCategoryController;
use App\Services\ListCategoryService;
use Tests\TestCase;
use Mockery;

class ListCategoryControllerTest extends TestCase
{
    public function test_invoke_returns_categories_data()
    {
        // Arrange
        $categories = [
            ['id' => 1, 'name' => 'Cultural'],
            ['id' => 2, 'name' => 'Natural'],
        ];

        $result = new \App\Results\ListCategoryResult($categories);

        $service = Mockery::mock(ListCategoryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->andReturn($result);

        $controller = new ListCategoryController($service);

        // Act
        ob_start();
        $controller();
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals('Cultural', $response['data'][0]['name']);
    }

    public function test_invoke_returns_empty_array_when_no_categories()
    {
        // Arrange
        $result = new \App\Results\ListCategoryResult([]);

        $service = Mockery::mock(ListCategoryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->andReturn($result);

        $controller = new ListCategoryController($service);

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
