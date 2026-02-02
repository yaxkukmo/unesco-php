<?php

namespace Tests\Unit\Filters;

use App\Filters\ListSiteFilter;
use Tests\TestCase;

class ListSiteFilterTest extends TestCase
{
    public function test_from_http_with_default_values()
    {
        // Arrange
        $query = [];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(1, $filter->page);
        $this->assertEquals(20, $filter->perPage);
        $this->assertNull($filter->country);
        $this->assertNull($filter->category);
    }

    public function test_from_http_with_custom_values()
    {
        // Arrange
        $query = [
            'page' => '3',
            'perPage' => '50',
            'country' => 'Poland',
            'category' => 'Cultural',
        ];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(3, $filter->page);
        $this->assertEquals(50, $filter->perPage);
        $this->assertEquals('Poland', $filter->country);
        $this->assertEquals('Cultural', $filter->category);
    }

    public function test_from_http_enforces_minimum_page()
    {
        // Arrange
        $query = ['page' => '-5'];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(1, $filter->page);
    }

    public function test_from_http_enforces_minimum_per_page()
    {
        // Arrange
        $query = ['perPage' => '0'];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(1, $filter->perPage);
    }

    public function test_from_http_enforces_maximum_per_page()
    {
        // Arrange
        $query = ['perPage' => '500'];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(100, $filter->perPage);
    }

    public function test_from_http_enforces_maximum_page()
    {
        // Arrange
        $query = ['page' => '999999'];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(10000, $filter->page);
    }

    public function test_from_http_truncates_long_country_name()
    {
        // Arrange
        $longCountry = str_repeat('A', 300);
        $query = ['country' => $longCountry];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(255, strlen($filter->country));
        $this->assertEquals(str_repeat('A', 255), $filter->country);
    }

    public function test_from_http_truncates_long_category_name()
    {
        // Arrange
        $longCategory = str_repeat('B', 300);
        $query = ['category' => $longCategory];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(255, strlen($filter->category));
        $this->assertEquals(str_repeat('B', 255), $filter->category);
    }

    public function test_from_http_handles_non_numeric_page()
    {
        // Arrange
        $query = ['page' => 'abc'];

        // Act
        $filter = ListSiteFilter::fromHttp($query);

        // Assert
        $this->assertEquals(1, $filter->page);
    }
}
