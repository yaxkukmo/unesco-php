<?php

declare(strict_types=1);

namespace App\Domain\Category\Entity;


use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Domain\Category\Entity\Category;
use App\Domain\Category\Event\CategoryCreated;

final class Category {
  private array $events = [];

  private function __construct(private ?CategoryId $id, private CategoryName $name) { }

  public static function create(CategoryName $name): self {
    $category = new self(null, $name);
    $category->events[] = new CategoryCreated($name);
    return $category;
  }

  public static function fromPersistence(CategoryId $id, CategoryName $name): self {
    return new self($id, $name);
  }

  public function rename(CategoryName $newName): void {
    $this->name = $newName;
  }

  public function id(): ?CategoryId {
    return $this->id;
  }

  public function name(): CategoryName {
    return $this->name;
  }

  public function releaseEvents(): array {
    $events = $this->events;
    $this->events = [];
    return $events;
  }
}
