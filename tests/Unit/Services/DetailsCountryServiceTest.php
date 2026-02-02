<?php

namespace Tests\Unit\Services;

use App\Services\DetailsCountryService;
use App\Repositories\CountryRepositoryInterface;
use App\Models\Country;
use Tests\TestCase;
use Mockery;

class DetailsCountryServiceTest extends TestCase
{
    public function test_invoke_returns_country_when_found()
    {
        // Arrange
        $countryId = 1;
        $expectedCountry = Mockery::mock(Country::class);

        $repository = Mockery::mock(CountryRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($countryId)
            ->andReturn($expectedCountry);

        $service = new DetailsCountryService($repository);

        // Act
        $result = $service->handle($countryId);

        // Assert
        $this->assertSame($expectedCountry, $result);
    }

    public function test_invoke_returns_null_when_not_found()
    {
        // Arrange
        $countryId = 999;

        $repository = Mockery::mock(CountryRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($countryId)
            ->andReturn(null);

        $service = new DetailsCountryService($repository);

        // Act
        $result = $service->handle($countryId);

        // Assert
        $this->assertNull($result);
    }
}
