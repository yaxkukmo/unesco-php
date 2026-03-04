<?php

declare(strict_types=1);

namespace App\Application\Query\ListSites;

final class ListSitesQuery {
  public function __construct(
    public readonly int $page = 1,
    public readonly int $perPage = 20,
    public readonly ?int $countryId = null,
    public readonly ?int $categoryId = null,
  ) {}
}
