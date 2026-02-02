<?php

namespace Tests\Unit\Controllers;

use App\Controllers\DetailsCountryController;
use App\Services\DetailsCountryService;
use App\Models\Country;
use Tests\TestCase;
use Mockery;

class DetailsCountryControllerTest extends TestCase
{
    public function test_invoke_returns_country_data_when_found()
    {
        // Arrange
        $countryId = 1;
        $mockCountry = Mockery::mock(Country::class)->makePartial();
        $mockCountry->shouldAllowMockingProtectedMethods();

        $service = Mockery::mock(DetailsCountryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($countryId)
            ->andReturn($mockCountry);

        $controller = new DetailsCountryController($service);

        // Act
        ob_start();
        $controller($countryId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('data', $response);
        $this->assertNotNull($response['data']);
    }

    public function test_invoke_returns_404_when_country_not_found()
    {
        // Arrange
        $countryId = 999;

        $service = Mockery::mock(DetailsCountryService::class);
        $service->shouldReceive('handle')
            ->once()
            ->with($countryId)
            ->andReturn(null);

        $controller = new DetailsCountryController($service);

        // Act
        ob_start();
        $controller($countryId);
        $output = ob_get_clean();

        // Assert
        $response = json_decode($output, true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Country not found', $response['error']);
    }
}
