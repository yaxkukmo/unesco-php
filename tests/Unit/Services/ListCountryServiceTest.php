<?php

namespace Tests\Unit\Services;

use App\Services\ListCountryService;
use App\Repositories\CountryRepositoryInterface;
use Tests\TestCase;
use Mockery;

class ListCountryServiceTest extends TestCase
{
    public function test_invoke_returns_countries_result()
    {
        // Arrange
        $expectedCountries = [
            ['id' => 1, 'name' => 'Poland'],
            ['id' => 2, 'name' => 'France'],
        ];

        $repository = Mockery::mock(CountryRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->andReturn($expectedCountries);

        $service = new ListCountryService($repository);

        // Act
        $result = $service->handle();

        // Assert
        $this->assertInstanceOf(\App\Results\ListCountryResult::class, $result);
        $this->assertSame($expectedCountries, $result->countries);
    }

    public function test_invoke_returns_empty_result_when_no_countries()
    {
        // Arrange
        $repository = Mockery::mock(CountryRepositoryInterface::class);
        $repository->shouldReceive('findAll')
            ->once()
            ->andReturn([]);

        $service = new ListCountryService($repository);

        // Act
        $result = $service->handle();

        // Assert
        $this->assertInstanceOf(\App\Results\ListCountryResult::class, $result);
        $this->assertEmpty($result->countries);
    }
}
