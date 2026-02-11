<?php

declare(strict_types=1);

namespace App\Domain\Country\Event;

use App\Domain\Country\ValueObject\CountryName;
use DateTimeImmutable;

final class CountryCreated {
  public readonly DateTimeImmutable $occuredAt;

  public function __construct(public readonly CountryName $name) {
    $this->occuredAt = new DateTimeImmutable();
  }

}
