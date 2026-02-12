<?php

declare(strict_types=1);

namespace App\Application\Query\ListSites;

use App\Domain\Site\Repository\SiteQueryRepositoryInterface;

final class ListSitesHandler {
  public function __construct(
    private SiteQueryRepositoryInterface $repository,
  ) {}

  public function handle(ListSitesQuery $query): array {
    $sites = $this->repository->findPaginated($query);
    $total = $this->repository->count($query);

    return [
      'data' => $sites,
      'meta' => [
        'total' => $total,
        'page' => $query->page,
        'perPage' => $query->perPage,
        'lastPage' => (int) ceil($total / $query->perPage),
      ],
    ];
  }
}
