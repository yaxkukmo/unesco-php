<?php

namespace App\Services;

use App\Filters\FilterInterface;
use App\Results\ListSiteResult;
use App\Repositories\SiteRepositoryInterface;

class ListSiteService {

  public function __construct(protected SiteRepositoryInterface $repository) {}

  public function handle(FilterInterface $filter): ListSiteResult {
    $sites = $this->repository->findAll($filter);
    $total = $this->repository->count($filter);
    return new ListSiteResult($sites, $total);
  }
}
