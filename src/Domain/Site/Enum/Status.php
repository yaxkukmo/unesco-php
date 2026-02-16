<?php

declare(strict_types=1);

namespace App\Domain\Site\Enum;

enum Status {
  case Pending;
  case Approved;
  case Rejected;

  public static function from(string $value): self {
    return match($value) {
      'Pending' => self::Pending,
      'Approved' => self::Approved,
      'Rejected' => self::Rejected,
      default => throw new \ValueError("Invalid status {$value}"),
    };
  }
}
