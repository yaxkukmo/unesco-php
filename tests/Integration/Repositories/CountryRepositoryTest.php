<?php

namespace Tests\Integration\Repositories;

use App\Repositories\CountryRepository;
use App\Models\Country;
use Tests\DatabaseTestCase;

class CountryRepositoryTest extends DatabaseTestCase
{
    private CountryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CountryRepository();
    }

    public function test_find_by_id_returns_country_when_exists()
    {
        // Arrange
        $this->seed('countries', [
            'id' => 1,
            'name' => 'Poland',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Act
        $country = $this->repository->findById(1);

        // Assert
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals(1, $country->id);
        $this->assertEquals('Poland', $country->name);
    }

    public function test_find_by_id_returns_null_when_not_exists()
    {
        // Act
        $country = $this->repository->findById(999);

        // Assert
        $this->assertNull($country);
    }

    public function test_find_all_returns_countries_ordered_by_name()
    {
        // Arrange
        $this->seed('countries', [
            ['id' => 1, 'name' => 'Poland', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'France', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Austria', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        // Act
        $countries = $this->repository->findAll();

        // Assert
        $this->assertCount(3, $countries);
        $this->assertEquals('Austria', $countries[0]['name']); // Alphabetically first
        $this->assertEquals('France', $countries[1]['name']);
        $this->assertEquals('Poland', $countries[2]['name']);
    }

    public function test_find_all_returns_empty_array_when_no_countries()
    {
        // Act
        $countries = $this->repository->findAll();

        // Assert
        $this->assertIsArray($countries);
        $this->assertEmpty($countries);
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

        $this->seed('sites', [
            ['id' => 1, 'wikidata_id' => 'Q1', 'name' => 'Site 1', 'country_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'wikidata_id' => 'Q2', 'name' => 'Site 2', 'country_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        // Act
        $countries = $this->repository->findAll();

        // Assert
        $this->assertArrayHasKey('sites_count', $countries[0]);
        $this->assertEquals(2, $countries[0]['sites_count']);
    }
}
