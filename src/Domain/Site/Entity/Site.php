<?php

declare(strict_types=1);

namespace App\Domain\Site\Entity;

use App\Domain\Site\ValueObject\Id;
use App\Domain\Site\ValueObject\Name;
use App\Domain\Site\ValueObject\Description;
use App\Domain\Site\ValueObject\ExternalUrl;
use App\Domain\Site\ValueObject\Coordinates;
use App\Domain\Site\Enum\Status;
use App\Domain\Site\Event\SiteCreated;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Category\ValueObject\CategoryId;
use DomainException;

final class Site {
  private array $events = [];
  private array $categoryIds = [];

  private function __construct(
    private Id $id,
    private Name $name,
    private ?Description $description,
    private ?ExternalUrl $externalUrl,
    private ?Coordinates $coordinates,
    private ?CountryId $countryId,
    private Status $status
  ) { }

  public static function create(Id $id, Name $name, ?Description $description, ?ExternalUrl $externalUrl, ?CountryId $countryId, ?Coordinates $coordinates): self {
    $status = Status::Pending;
    $site = new self($id, $name, $description, $externalUrl, $coordinates, $countryId, $status);
    $site->events[] = new SiteCreated($name, $coordinates);
    return $site;
  }

  public static function fromPersistence(Id $id, Name $name, ?Description $description, ?ExternalUrl $externalUrl, ?Coordinates $coordinates, ?CountryId $countryId, Status $status, array $categoryIds = []): self {
    $site = new self($id, $name, $description, $externalUrl, $coordinates, $status);
    $site->categoryIds = $categoryIds;
    return $site;
  }

  public function attachCategory(CategoryId $categoryId): void {
    $this->categoryIds[$categoryId->value] = $categoryId;
  }

  public function categoryIds(): array {
    return array_values($this->categoryIds);
  }

  public function approve(): void {
    if ($this->status !== Status::Pending) throw new DomainException('Cannot approve site with status other than pending');
    $this->status = Status::Approved;
  }

  public function reject(): void {
    if ($this->status !== Status::Pending) throw new DomainException('Cannot reject site with status other than pending');
    $this->status = Status::Rejected;
  }

  public function id(): ?Id {
    return $this->id;
  }

  public function name(): Name {
    return $this->name;
  }

  public function description(): ?Description {
    return $this->description;
  }

  public function externalUrl(): ?ExternalUrl {
    return $this->externalUrl;
  }

  public function coordinates(): ?Coordinates {
    return $this->coordinates;
  }

  public function status(): Status {
    return $this->status;
  }

  public function rename(Name $newName): void {
    $this->name = $newName;
  }

  public function releaseEvents(): array {
    $events = $this->events;
    $this->events = [];
    return $events;
  }
}
