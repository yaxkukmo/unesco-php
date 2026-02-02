<?php

namespace App\Services;

use App\Repositories\CountryRepositoryInterface;
use App\Models\Country;

class DetailsCountryService {

  public function __construct(protected CountryRepositoryInterface $repository) {}

  public function handle(int $id): ?Country {
    return $this->repository->findById($id);
  }
}
