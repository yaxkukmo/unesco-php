<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Category\Repository\CategoryQueryRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Model\Category;

class CategoryQueryRepository implements CategoryQueryRepositoryInterface {

  #[\Override]
  public function findById(int $id): ?array {
    return Category::with('sites')->find($id)?->toArray();
  }

  #[\Override]
  public function findAll(): array {
    return Category::orderBy('name')
      ->get()->toArray();
  }

  #[\Override]
  public function findAllWithSitesCount(): array {
    return Category::withCount('sites')
      ->orderBy('name')
      ->get()->toArray();
  }
}
