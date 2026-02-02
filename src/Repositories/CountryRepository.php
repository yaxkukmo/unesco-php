<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use App\Filters\FilterInterface;

class CountryRepository implements CountryRepositoryInterface {

  public function findById(int $id): ?Model {
    return Country::with('sites')->find($id);
  }

  public function findAll(?FilterInterface $filter = null): array {
    return Country::withCount('sites')
      ->orderBy('name')
      ->get()->toArray();
  }

  public function count(?FilterInterface $filter = null): int {
    return 0;
  }
}
