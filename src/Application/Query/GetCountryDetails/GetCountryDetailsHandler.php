<?php

declare(strict_types=1);

namespace App\Application\Query\GetCountryDetails;

use App\Domain\Country\Repository\CountryQueryRepositoryInterface;

final class GetCountryDetailsHandler {
  public function __construct(
    private CountryQueryRepositoryInterface $repository,
  ) {}

  public function handle(GetCountryDetailsQuery $query): ?array {
    return $this->repository->findById($query->id);
  }
}
