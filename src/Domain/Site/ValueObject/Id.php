<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class Id {
  private readonly string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function generate(): self {
    return new self(uuid_create(UUID_TYPE_RANDOM));
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') {
      throw new InvalidArgumentException('Site uuid cannot be empty');
    }
    if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value)) {
      throw new InvalidArgumentException('Invalid UUID format');
    }
    return new self($value);
  }

  public function value(): string {
    return $this->value;
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }
}
