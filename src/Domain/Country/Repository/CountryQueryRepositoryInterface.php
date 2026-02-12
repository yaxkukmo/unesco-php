<?php

declare(strict_types=1);

namespace App\Domain\Country\Repository;

interface CountryQueryRepositoryInterface {
  public function findById(int $id): ?array;
  public function findAll(): array;
  public function findAllWithSitesCount(): array;
}
