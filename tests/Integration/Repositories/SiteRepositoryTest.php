<?php

namespace Tests\Integration\Repositories;

use App\Repositories\SiteRepository;
use App\Filters\ListSiteFilter;
use App\Models\Site;
use Tests\DatabaseTestCase;

class SiteRepositoryTest extends DatabaseTestCase
{
    private SiteRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SiteRepository();
    }

    public function test_find_by_id_returns_site_when_exists()
    {
        // Arrange
        $this->seed('countries', [
            'id' => 1,
            'name' => 'Poland',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->seed('sites', [
            'id' => 1,
            'wikidata_id' => 'Q123',
            'name' => 'Historic Centre of Kraków',
            'description' => 'UNESCO World Heritage Site',
            'latitude' => 50.0614,
            'longitude' => 19.9366,
            'country_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Act
        $site = $this->repository->findById(1);

        // Assert
        $this->assertInstanceOf(Site::class, $site);
        $this->assertEquals(1, $site->id);
        $this->assertEquals('Historic Centre of Kraków', $site->name);
        $this->assertEquals('Q123', $site->wikidata_id);
    }

    public function test_find_by_id_returns_null_when_not_exists()
    {
        // Act
        $site = $this->repository->findById(999);

        // Assert
        $this->assertNull($site);
    }

    public function test_find_by_filter_returns_sites_with_pagination()
    {
        // Arrange
        $this->seed('countries', [
            'id' => 1,
            'name' => 'Poland',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $this->seed('sites', [
                'id' => $i,
                'wikidata_id' => "Q{$i}",
                'name' => "Site {$i}",
                'country_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $filter = new ListSiteFilter(1, 3, null, null);

        // Act
        $sites = $this->repository->findAll($filter);

        // Assert
        $this->assertCount(3, $sites);
        $this->assertEquals('Site 1', $sites[0]['name']);
    }

    public function test_find_by_filter_with_country_filter()
    {
        // Arrange
        $this->seed('countries', [
            ['id' => 1, 'name' => 'Poland', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'France', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        $this->seed('sites', [
            ['id' => 1, 'wikidata_id' => 'Q1', 'name' => 'Site Poland', 'country_id' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'wikidata_id' => 'Q2', 'name' => 'Site France', 'country_id' => 2, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);

        $filter = new ListSiteFilter(1, 10, 'Poland', null);

        // Act
        $sites = $this->repository->findAll($filter);

        // Assert
        $this->assertCount(1, $sites);
        $this->assertEquals('Site Poland', $sites[0]['name']);
    }

    public function test_count_returns_correct_total()
    {
        // Arrange
        $this->seed('countries', [
            'id' => 1,
            'name' => 'Poland',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $this->seed('sites', [
                'id' => $i,
                'wikidata_id' => "Q{$i}",
                'name' => "Site {$i}",
                'country_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $filter = new ListSiteFilter(1, 20, null, null);

        // Act
        $count = $this->repository->count($filter);

        // Assert
        $this->assertEquals(10, $count);
    }
}
