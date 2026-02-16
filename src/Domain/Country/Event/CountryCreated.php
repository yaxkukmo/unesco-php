<?php

declare(strict_types=1);

namespace App\Domain\Country\Event;

use App\Domain\Country\ValueObject\CountryName;
use DateTimeImmutable;

final class CountryCreated {

  public function __construct(
    public readonly CountryName $name,
    public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
  ) { }
}
