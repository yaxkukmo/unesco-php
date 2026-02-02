<?php

namespace Tests\Unit\Services;

use App\Services\DetailsCategoryService;
use App\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Tests\TestCase;
use Mockery;

class DetailsCategoryServiceTest extends TestCase
{
    public function test_invoke_returns_category_when_found()
    {
        // Arrange
        $categoryId = 1;
        $expectedCategory = Mockery::mock(Category::class);

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($categoryId)
            ->andReturn($expectedCategory);

        $service = new DetailsCategoryService($repository);

        // Act
        $result = $service->handle($categoryId);

        // Assert
        $this->assertSame($expectedCategory, $result);
    }

    public function test_invoke_returns_null_when_not_found()
    {
        // Arrange
        $categoryId = 999;

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($categoryId)
            ->andReturn(null);

        $service = new DetailsCategoryService($repository);

        // Act
        $result = $service->handle($categoryId);

        // Assert
        $this->assertNull($result);
    }
}
