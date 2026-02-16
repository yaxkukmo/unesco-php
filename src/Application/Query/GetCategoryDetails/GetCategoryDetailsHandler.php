<?php

declare(strict_types=1);

namespace App\Application\Query\GetCategoryDetails;

use App\Domain\Category\Repository\CategoryQueryRepositoryInterface;

final class GetCategoryDetailsHandler {
  public function __construct(
    private CategoryQueryRepositoryInterface $repository,
  ) {}

  public function handle(GetCategoryDetailsQuery $query): ?array {
    return $this->repository->findById($query->id);
  }
}
