<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Site\Repository\SiteQueryRepositoryInterface;
use App\Application\Query\ListSites\ListSitesQuery;
use App\Infrastructure\Persistence\Eloquent\Model\Site;
use Illuminate\Database\Eloquent\Builder;

class SiteQueryRepository implements SiteQueryRepositoryInterface {

  #[\Override]
  public function findById(string $id): ?array {
    return Site::with(['country', 'categories', 'unescoSites'])
      ->where('id', $id)
      ->first()
      ?->toArray();
  }

  #[\Override]
  public function findPaginated(ListSitesQuery $query): array {
    $builder = Site::with(['country', 'categories', 'unescoSites']);
    $this->applyFilters($builder, $query);

    return $builder
      ->offset(($query->page - 1) * $query->perPage)
      ->limit($query->perPage)
      ->get()
      ->toArray();
  }

  #[\Override]
  public function count(ListSitesQuery $query): int {
    $builder = Site::query();
    $this->applyFilters($builder, $query);
    return $builder->count();
  }

  private function applyFilters(Builder $builder, ListSitesQuery $query): void {
    if ($query->countryId !== null) {
      $builder->where('country_id', $query->countryId);
    }
    if ($query->categoryId !== null) {
      $builder->whereHas('categories', function (Builder $q) use ($query) {
        $q->where('id', $query->categoryId);
      });
    }
  }
}
