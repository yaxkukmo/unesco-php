<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use App\Results\ListCategoryResult;

class ListCategoryService {

  public function __construct(protected CategoryRepositoryInterface $repository) {}

  public function handle(): ListCategoryResult {
    $result = $this->repository->findAll();
    return new ListCategoryResult($result);
  }
}
