<?php

namespace Tests\Unit\Services;

use App\Services\DetailsSiteService;
use App\Repositories\SiteRepositoryInterface;
use App\Models\Site;
use Tests\TestCase;
use Mockery;

class DetailsSiteServiceTest extends TestCase
{
    public function test_invoke_returns_site_when_found()
    {
        // Arrange
        $siteId = 1;
        $expectedSite = Mockery::mock(Site::class);

        $repository = Mockery::mock(SiteRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($siteId)
            ->andReturn($expectedSite);

        $service = new DetailsSiteService($repository);

        // Act
        $result = $service->handle($siteId);

        // Assert
        $this->assertSame($expectedSite, $result);
    }

    public function test_invoke_returns_null_when_not_found()
    {
        // Arrange
        $siteId = 999;

        $repository = Mockery::mock(SiteRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($siteId)
            ->andReturn(null);

        $service = new DetailsSiteService($repository);

        // Act
        $result = $service->handle($siteId);

        // Assert
        $this->assertNull($result);
    }
}
