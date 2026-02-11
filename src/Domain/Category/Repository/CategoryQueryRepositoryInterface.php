<?php

declare(strict_types=1);

namespace App\Domain\Category\Repository;

interface CategoryQueryRepositoryInterface {
  public function findById(int $id): ?array;
  public function findAll(): array;
  public function findAllWithSitesCount(): array;
}
