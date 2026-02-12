<?php

declare(strict_types=1);

namespace App\Domain\Site\Repository;

use App\Application\Query\ListSites\ListSitesQuery;

interface SiteQueryRepositoryInterface {
  public function findById(string $id): ?array;
  public function findPaginated(ListSitesQuery $query): array;
  public function count(ListSitesQuery $query): int;
}
