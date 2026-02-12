<?php

declare(strict_types=1);

namespace App\Domain\Country\ValueObject;

use InvalidArgumentException;

final class CountryId {
  private readonly int $value;

  private function __construct(int $value) {
    $this->value = $value;
  }

  public static function create(int $value): self {
    if ($value <= 0) throw new InvalidArgumentException('Id cannot be less than 1');
    return new self($value);
  }

  public function value(): int {
    return $this->value;
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }
}
