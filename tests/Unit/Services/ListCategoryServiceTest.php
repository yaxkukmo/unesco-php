<?php

namespace Tests\Unit\Services;

use App\Services\ListCategoryService;
use App\Repositories\CategoryRepositoryInterface;
use Tests\TestCase;
use Mockery;

class ListCategoryServiceTest extends TestCase
{
    public function test_invoke_returns_categories_result()
    {
        // Arrange
        $expectedCategories = [
            ['id' => 1, 'name' => 'Cultural'],
            ['id' => 2, 'name' => 'Natural'],
        ];

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->andReturn($expectedCategories);

        $service = new ListCategoryService($repository);

        // Act
        $result = $service->handle();

        // Assert
        $this->assertInstanceOf(\App\Results\ListCategoryResult::class, $result);
        $this->assertSame($expectedCategories, $result->categories);
    }

    public function test_invoke_returns_empty_result_when_no_categories()
    {
        // Arrange
        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->andReturn([]);

        $service = new ListCategoryService($repository);

        // Act
        $result = $service->handle();

        // Assert
        $this->assertInstanceOf(\App\Results\ListCategoryResult::class, $result);
        $this->assertEmpty($result->categories);
    }
}
