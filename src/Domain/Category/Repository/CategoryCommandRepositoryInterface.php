<?php

declare(strict_types=1);

namespace App\Domain\Category\Repository;

use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\Entity\Category;

interface CategoryCommandRepositoryInterface {
  public function findById(CategoryId $id): Category;
  public function save(Category $category): CategoryId;
  public function delete(CategoryId $id): void;
}
