<?php

namespace App\Services;

use App\Repositories\CountryRepositoryInterface;
use App\Results\ListCountryResult;

class ListCountryService {

  public function __construct(protected CountryRepositoryInterface $repository) {}

  public function handle(): ListCountryResult {
    $country = $this->repository->findAll();
    return new ListCountryResult($country);
  }
}
