<?php

namespace Tests\Unit\Controllers;

use App\Controllers\DetailsCategoryController;
use App\Services\DetailsCategoryService;
use App\Models\Category;
use Tests\TestCase;
use Mockery;

class DetailsCategoryControllerTest extends TestCase
{
    public function test_invoke_returns_category_data_when_found()
    {
        // Arrange
        $categoryId = 1;
        $mockCategory = Mockery::mock(Category::class)->makePartial();
        $mockCategory->shouldAllowMockingProtectedMethods();

        $service = Mockery::mock(DetailsCategoryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($categoryId)
            ->andReturn($mockCategory);

        $controller = new DetailsCategoryController($service);

        // Act
        ob_start();
        $controller($categoryId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertNotNull($response['data']);
    }

    public function test_invoke_returns_404_when_category_not_found()
    {
        // Arrange
        $categoryId = 999;

        $service = Mockery::mock(DetailsCategoryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($categoryId)
            ->andReturn(null);

        $controller = new DetailsCategoryController($service);

        // Act
        ob_start();
        $controller($categoryId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Category not found', $response['error']);
    }
}
