<?php

namespace App\Repositories;

use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface {
  public function findAll(?FilterInterface $filter = null): array;
  public function findById(int $id): ?Model;
  public function count(?FilterInterface $filter = null): int;
}
