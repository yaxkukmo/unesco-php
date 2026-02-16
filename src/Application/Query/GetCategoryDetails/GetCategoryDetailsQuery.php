<?php

declare(strict_types=1);

namespace App\Application\Query\GetCategoryDetails;

final class GetCategoryDetailsQuery {
  public function __construct(
    public readonly int $id,
  ) {}
}
