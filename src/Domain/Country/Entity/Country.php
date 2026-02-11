<?php

declare(strict_types=1);

namespace App\Domain\Country\Entity;

use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\CountryName;
use App\Domain\Country\Event\CountryCreated;

final class Country {
  private array $events = [];

  private function __construct(private ?CountryId $id, private CountryName $name) { }

  public static function create(CountryName $name): self {
    $country = new self(null, $name);
    $this->events[] = new CountryCreated($name);
    return $country;
  }

  public static function fromPersistence(CountryId $id, CountryName $name): self {
    return new self($id, $name);
  }

  public function id(): ?CountryId {
    return $this->id;
  }

  public function name(): CountryName {
    return $this->name;
  }

  public function rename(CountryName $newCountryName): void {
    $this->name = $newCountryName;
  }

  public function releaseEvents(): array {
    $events = $this->events;
    $this->events = [];
    return $events;
  }
}
