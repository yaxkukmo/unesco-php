<?php

declare(strict_types=1);

namespace App\Domain\Country\ValueObject;

use InvalidArgumentException;

final class CountryName {
  private readonly string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') throw new InvalidArgumentException('Name cannot be empty');
    if (mb_strlen($value) > 200) throw new InvalidArgumentException('Country name cannot exceed 200 characters');
    return new self($value);
  }

  public function value(): string {
    return $this->value;
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }
}
