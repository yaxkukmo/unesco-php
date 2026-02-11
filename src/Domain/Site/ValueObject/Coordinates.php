<?php

declare(strict_types=1);

namespace App\Domain\Site\ValueObject;

use InvalidArgumentException;

final class Coordinates {
  private string $latitude;
  private string $longitude;

  private function __construct(float $latitude, float $longitude) {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  public static function create(float $latitude, float $longitude): self {
    if ($latitude < -90 || $latitude > 90) throw new InvalidArgumentException('Latitude must be between -90 and 90');
    if ($longitude < -180 || $longitude > 180) throw new InvalidArgumentException('Longitude must be between -180 and 180');
    return new self($latitude, $longitute);
  }

  public function equals(self $other): bool {
    return $this->latitude === $other->latitude && $this->longitude === $other->longitude;
  }

  public function latitude(): float {
    return $this->latitude;
  }

  public function longitude(): float {
    return $this->longitude;
  }
}
