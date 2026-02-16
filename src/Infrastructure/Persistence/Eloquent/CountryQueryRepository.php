<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Country\Repository\CountryQueryRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Model\Country;

class CountryQueryRepository implements CountryQueryRepositoryInterface {

  #[\Override]
  public function findById(int $id): ?array {
    return Country::with('sites')->find($id)?->toArray();
  }

  #[\Override]
  public function findAll(): array {
    return Country::orderBy('name')
      ->get()->toArray();
  }

  #[\Override]
  public function findAllWithSitesCount(): array {
    return Country::withCount('sites')
      ->orderBy('name')
      ->get()->toArray();
  }
}
