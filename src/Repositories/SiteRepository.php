<?php

namespace App\Repositories;

use App\Models\Site;
use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SiteRepository implements SiteRepositoryInterface {

  public function findAll(?FilterInterface $filter = null): array {
    $query = Site::with(['country', 'categories', 'unescoSites']);
    $this->applyFilter($query, $filter);
    return $query->offset(($filter->page - 1) * $filter->perPage)
      ->limit($filter->perPage)
      ->get()->toArray();
    }

  public function findById(int $id): ?Model {
    return Site::with(['country', 'categories', 'unescoSites'])
      ->where('id', $id)
      ->first();
  }

  public function count(?FilterInterface $filter = null): int {
    $query = Site::query();
    $this->applyFilter($query, $filter);
    return $query->count();
  }

  private function applyFilter(Builder $query, FilterInterface $filter): void {

    if ($filter->country) {
      $query->whereHas('country', function ($q) use ($filter) {
        $q->where('id', $filter->country);
      });
    }
    if ($filter->category) {
      $query->whereHas('categories', function ($q) use ($filter) {
        $q->where('name', 'like', "%{$filter->category}%");
      });
    }
  }
}
