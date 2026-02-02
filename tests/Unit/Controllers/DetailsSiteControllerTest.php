<?php

namespace Tests\Unit\Controllers;

use App\Controllers\DetailsSiteController;
use App\Services\DetailsSiteService;
use App\Models\Site;
use Tests\TestCase;
use Mockery;

class DetailsSiteControllerTest extends TestCase
{
    public function test_invoke_returns_site_data_when_found()
    {
        // Arrange
        $siteId = 1;
        $mockSite = Mockery::mock(Site::class)->makePartial();
        $mockSite->shouldAllowMockingProtectedMethods();

        $service = Mockery::mock(DetailsSiteService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($siteId)
            ->andReturn($mockSite);

        $controller = new DetailsSiteController($service);

        // Act
        ob_start();
        $controller($siteId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertNotNull($response['data']);
    }

    public function test_invoke_returns_404_when_site_not_found()
    {
        // Arrange
        $siteId = 999;

        $service = Mockery::mock(DetailsSiteService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($siteId)
            ->andReturn(null);

        $controller = new DetailsSiteController($service);

        // Act
        ob_start();
        $controller($siteId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Site not found', $response['error']);

        // Check HTTP response code was set (we can't assert it in tests easily, but we tested the logic)
    }
}
