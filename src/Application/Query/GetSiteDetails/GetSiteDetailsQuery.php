<?php

declare(strict_types=1);

namespace App\Application\Query\GetSiteDetails;

final class GetSiteDetailsQuery {
  public function __construct(
    public readonly string $id,
  ) {}
}
