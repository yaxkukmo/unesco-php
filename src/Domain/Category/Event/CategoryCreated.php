<?php

declare(strict_types=1);

namespace App\Domain\Category\Event;

use App\Domain\Category\ValueObject\CategoryName;
use DateTimeImmutable;

final class CategoryCreated {
  public readonly DateTimeImmutable $occuredAt;

  public function __construct(public readonly CategoryName $name) {
    $this->occuredAt = new DateTimeImmutable();
  }

}
