<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class Id {
  private string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') throw new InvalidArgumentException('Site uuid cannot be empty');
    if (mb_strlen($value) > 64) throw new InvalidArgumentException('Site uuid cannot exceed 64 characters');
    return new self($value);
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }

  public function value(): string {
    return $this->value;
  }
}
