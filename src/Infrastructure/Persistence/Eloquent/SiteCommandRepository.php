<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Site\Entity\Site as DomainSite;
use App\Domain\Site\Repository\SiteCommandRepositoryInterface;
use App\Domain\Site\ValueObject\Id;
use App\Domain\Site\ValueObject\Name;
use App\Domain\Site\ValueObject\Description;
use App\Domain\Site\ValueObject\ExternalUrl;
use App\Domain\Site\ValueObject\Coordinates;
use App\Domain\Site\Enum\Status;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Category\ValueObject\CategoryId;
use App\Infrastructure\Persistence\Eloquent\Model\Site;
use DomainException;

class SiteCommandRepository implements SiteCommandRepositoryInterface {

  #[\Override]
  public function findById(Id $id): DomainSite {
    $model = Site::find($id->value());

    if ($model === null) {
      throw new DomainException("Site with id {$id->value()} not found");
    }

    $categoryIds = $model->categories()
      ->pluck('categories.id')
      ->map(fn(int $id) => CategoryId::create($id))
      ->all();

    return DomainSite::fromPersistence(
      Id::create($model->id),
      Name::create($model->name),
      $model->description !== null ? Description::create($model->description) : null,
      $model->external_url !== null ? ExternalUrl::create($model->external_url) : null,
      ($model->latitude === null || $model->longitude === null)
        ? null
        : Coordinates::create($model->latitude, $model->longitude),
      $model->country_id !== null ? CountryId::create($model->country_id) : null,
      Status::from($model->status),
      $categoryIds,
    );
  }

  #[\Override]
  public function save(DomainSite $site): void {
    $model = Site::updateOrCreate(
      ['id' => $site->id()->value()],
      [
        'name' => $site->name()->value(),
        'description' => $site->description()?->value(),
        'external_url' => $site->externalUrl()?->value(),
        'latitude' => $site->coordinates()?->latitude(),
        'longitude' => $site->coordinates()?->longitude(),
        'country_id' => $site->countryId()?->value(),
        'status' => $site->status()->name,
      ],
    );

    $categoryIds = array_map(
      fn(CategoryId $id) => $id->value(),
      $site->categoryIds(),
    );
    $model->categories()->sync($categoryIds);
  }

  #[\Override]
  public function delete(Id $id): void {
    $deleted = Site::destroy($id->value());
    if ($deleted === 0) {
      throw new DomainException("Site with id {$id->value()} not found");
    }
  }
}
