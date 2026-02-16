<?php

declare(strict_types=1);

namespace App\Application\Query\ListCategories;

use App\Domain\Category\Repository\CategoryQueryRepositoryInterface;

final class ListCategoriesHandler {
  public function __construct(
    private CategoryQueryRepositoryInterface $repository,
  ) {}

  public function handle(ListCategoriesQuery $query): array {
    return $this->repository->findAllWithSitesCount();
  }
}
