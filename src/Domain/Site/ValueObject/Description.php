<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class Description {
  private string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') throw new InvalidArgumentException('Site external URL cannot be empty');
    return new self($value);
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }

  public function value(): string {
    return $this->value;
  }
}
