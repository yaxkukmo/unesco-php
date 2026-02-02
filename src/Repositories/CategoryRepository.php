<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use App\Filters\FilterInterface;

class CategoryRepository implements CategoryRepositoryInterface {

  public function findById(int $id): ?Model {
    return Category::with('sites')->find($id);
  }

  public function findAll(?FilterInterface $filter = null): array {
    return Category::withCount('sites')
      ->orderBy('name')
      ->get()->toArray();
  }

  public function count(?FilterInterface $filter = null): int {
    return 0;
  }
}
