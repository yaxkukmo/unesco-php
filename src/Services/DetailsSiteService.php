<?php

namespace App\Services;

use App\Models\Site;
use App\Repositories\SiteRepositoryInterface;

class DetailsSiteService {

  public function __construct(protected SiteRepositoryInterface $repository) {}

  public function handle(int $id): ?Site {
    return $this->repository->findById($id);
  }
}
