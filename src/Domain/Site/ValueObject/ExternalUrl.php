<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class ExternalUrl {
  private string $value;

  private function __construct(string $value) {
    $this->value = $value;
  }

  public static function create(string $value): self {
    $value = trim($value);
    if ($value === '') throw new InvalidArgumentException('Site external URL cannot be empty');
    if (filter_var($value, FILTER_VALIDATE_URL) === false) throw new InvalidArgumentException('Site external URL is not valid URL');
    return new self($value);
  }

  public function equals(self $other): bool {
    return $this->value === $other->value;
  }

  public function value(): string {
    return $this->value;
  }
}
