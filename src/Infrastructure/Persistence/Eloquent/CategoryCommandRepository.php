<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Category\Entity\Category as DomainCategory;
use App\Domain\Category\Repository\CategoryCommandRepositoryInterface;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Infrastructure\Persistence\Eloquent\Model\Category;
use DomainException;

class CategoryCommandRepository implements CategoryCommandRepositoryInterface {

  #[\Override]
  public function findById(CategoryId $id): DomainCategory {
    $model = Category::find($id->value());

    if ($model === null) {
      throw new DomainException("Category with id {$id->value()} not found");
    }

    return DomainCategory::fromPersistence(
      CategoryId::create($model->id),
      CategoryName::create($model->name),
    );
  }

  #[\Override]
  public function delete(CategoryId $id): void {
    $deleted = Category::destroy($id->value());
    if ($deleted === 0) {
      throw new DomainException("Category with id {$id->value()} not found");
    }
  }

  #[\Override]
  public function save(DomainCategory $category): CategoryId {
    $model = Category::updateOrCreate(
      ['id' => $category->id()?->value()],
      ['name' => $category->name()->value()],
    );

    return CategoryId::create($model->id);
  }
}
