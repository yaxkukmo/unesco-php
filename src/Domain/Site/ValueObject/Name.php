<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class Name {
  private readonly string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') throw new InvalidArgumentException('Site name cannot be empty');
    if (mb_strlen($value) > 255) throw new InvalidArgumentException('Site name cannot exceed 255 characters');
    return new self($value);
  }

  public function value(): string {
    return $this->value;
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }
}
