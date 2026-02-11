<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

final class Status {
  private string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {

    return new self($value);
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }

  public function value(): string {
    return $this->value;
  }
}
