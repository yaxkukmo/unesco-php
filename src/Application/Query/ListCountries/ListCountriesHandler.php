<?php

declare(strict_types=1);

namespace App\Application\Query\ListCountries;

use App\Domain\Country\Repository\CountryQueryRepositoryInterface;

final class ListCountriesHandler {
  public function __construct(
    private CountryQueryRepositoryInterface $repository,
  ) {}

  public function handle(ListCountriesQuery $query): array {
    return $this->repository->findAllWithSitesCount();
  }
}
