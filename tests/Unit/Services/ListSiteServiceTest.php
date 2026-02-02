<?php

namespace Tests\Unit\Services;

use App\Services\ListSiteService;
use App\Repositories\SiteRepositoryInterface;
use App\Filters\ListSiteFilter;
use App\Results\ListSiteResult;
use Tests\TestCase;
use Mockery;

class ListSiteServiceTest extends TestCase
{
    public function test_invoke_returns_list_site_result()
    {
        // Arrange
        $filter = new ListSiteFilter(1, 20, null, null);
        $expectedSites = [
            ['id' => 1, 'name' => 'Site 1'],
            ['id' => 2, 'name' => 'Site 2'],
        ];
        $expectedTotal = 2;

        $repository = Mockery::mock(SiteRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->with($filter)
            ->andReturn($expectedSites);
        $repository->shouldReceive('count')
            ->once()
            ->with($filter)
            ->andReturn($expectedTotal);

        $service = new ListSiteService($repository);

        // Act
        $result = $service->handle($filter);

        // Assert
        $this->assertInstanceOf(ListSiteResult::class, $result);
        $this->assertSame($expectedSites, $result->sites);
        $this->assertSame($expectedTotal, $result->total);
    }

    public function test_invoke_with_filters()
    {
        // Arrange
        $filter = new ListSiteFilter(2, 10, 'Poland', 'Cultural');
        $expectedSites = [['id' => 1, 'name' => 'Krakow']];
        $expectedTotal = 1;

        $repository = Mockery::mock(SiteRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->with($filter)
            ->andReturn($expectedSites);
        $repository->shouldReceive('count')
            ->once()
            ->with($filter)
            ->andReturn($expectedTotal);

        $service = new ListSiteService($repository);

        // Act
        $result = $service->handle($filter);

        // Assert
        $this->assertInstanceOf(ListSiteResult::class, $result);
        $this->assertCount(1, $result->sites);
        $this->assertEquals(1, $result->total);
    }
}
