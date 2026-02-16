<?php

declare(strict_types=1);

namespace App\Application\Query\GetSiteDetails;

use App\Domain\Site\Repository\SiteQueryRepositoryInterface;

final class GetSiteDetailsHandler {
  public function __construct(
    private SiteQueryRepositoryInterface $repository,
  ) {}

  public function handle(GetSiteDetailsQuery $query): ?array {
    return $this->repository->findById($query->id);
  }
}
