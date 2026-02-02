<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

class DetailsCategoryService {

  public function __construct(protected CategoryRepositoryInterface $repository) {}

  public function handle(int $id): ?Category {
    return $this->repository->findById($id);
  }
}
