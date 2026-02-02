<?php

namespace Tests\Integration\Repositories;

use App\Repositories\CategoryRepository;
use App\Models\Category;
use Tests\DatabaseTestCase;

class CategoryRepositoryTest extends DatabaseTestCase
{
    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
    }

    public function test_find_by_id_returns_category_when_exists()
    {
        // Arrange
        $this->seed('categories', [
            'id' => 1,
            'name' => 'Cultural',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Act
        $category = $this->repository->findById(1);

        // Assert
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals(1, $category->id);
        $this->assertEquals('Cultural', $category->name);
    }

    public function test_find_by_id_returns_null_when_not_exists()
    {
        // Act
        $category = $this->repository->findById(999);

        // Assert
        $this->assertNull($category);
    }

    public function test_find_all_returns_categories_ordered_by_name()
    {
        // Arrange
        $this->seed('categories', [
            ['id' => 1, 'name' => 'Natural', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Cultural', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Mixed', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        // Act
        $categories = $this->repository->findAll();

        // Assert
        $this->assertCount(3, $categories);
        $this->assertEquals('Cultural', $categories[0]['name']); // Alphabetically first
        $this->assertEquals('Mixed', $categories[1]['name']);
        $this->assertEquals('Natural', $categories[2]['name']);
    }

    public function test_find_all_returns_empty_array_when_no_categories()
    {
        // Act
        $categories = $this->repository->findAll();

        // Assert
        $this->assertIsArray($categories);
        $this->assertEmpty($categories);
    }

    public function test_find_all_includes_sites_count()
    {
        // Arrange
        $this->seed('countries', [
            'id' => 1,
            'name' => 'Poland',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->seed('categories', [
            'id' => 1,
            'name' => 'Cultural',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->seed('sites', [
            ['id' => 1, 'wikidata_id' => 'Q1', 'name' => 'Site 1', 'country_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'wikidata_id' => 'Q2', 'name' => 'Site 2', 'country_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        $this->seed('site_categories', [
            ['site_id' => 1, 'category_id' => 1],
            ['site_id' => 2, 'category_id' => 1],
        ]);

        // Act
        $categories = $this->repository->findAll();

        // Assert
        $this->assertArrayHasKey('sites_count', $categories[0]);
        $this->assertEquals(2, $categories[0]['sites_count']);
    }
}
