<?php

namespace Tests\Unit\Controllers;

use App\Controllers\ListSiteController;
use App\Services\ListSiteService;
use App\Results\ListSiteResult;
use App\Filters\ListSiteFilter;
use Tests\TestCase;
use Mockery;

class ListSiteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_GET = []; // Clear GET parameters
    }

    public function test_invoke_returns_paginated_sites()
    {
        // Arrange
        $sites = [
            ['id' => 1, 'name' => 'Site 1'],
            ['id' => 2, 'name' => 'Site 2'],
        ];
        $total = 10;

        $result = new ListSiteResult($sites, $total);

        $service = Mockery::mock(ListSiteService::class);
        $service->shouldReceive('handle')
            ->once()
            ->andReturn($result);

        $controller = new ListSiteController($service);

        // Act
        ob_start();
        $controller();
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals(10, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['page']);
    }

    public function test_invoke_with_query_parameters()
    {
        // Arrange
        $_GET = [
            'page' => '2',
            'perPage' => '5',
            'country' => 'Poland',
        ];

        $sites = [['id' => 1, 'name' => 'Krakow']];
        $total = 1;

        $result = new ListSiteResult($sites, $total);

        $service = Mockery::mock(ListSiteService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with(Mockery::on(function ($filter) {
                return $filter instanceof ListSiteFilter
                    && $filter->page === 2
                    && $filter->perPage === 5
                    && $filter->country === 'Poland';
            }))
            ->andReturn($result);

        $controller = new ListSiteController($service);

        // Act
        ob_start();
        $controller();
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(2, $response['meta']['page']);
    }
}
