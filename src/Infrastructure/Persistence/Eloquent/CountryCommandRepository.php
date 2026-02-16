<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Country\Entity\Country as DomainCountry;
use App\Domain\Country\Repository\CountryCommandRepositoryInterface;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\CountryName;
use App\Infrastructure\Persistence\Eloquent\Model\Country;
use DomainException;

class CountryCommandRepository implements CountryCommandRepositoryInterface {

  #[\Override]
  public function findById(CountryId $id): DomainCountry {
    $model = Country::find($id->value());

    if ($model === null) {
      throw new DomainException("Country with id {$id->value()} not found");
    }

    return DomainCountry::fromPersistence(
      CountryId::create($model->id),
      CountryName::create($model->name),
    );
  }

  #[\Override]
  public function delete(CountryId $id): void {
    $deleted = Country::destroy($id->value());
    if ($deleted === 0) {
      throw new DomainException("Country with id {$id->value()} not found");
    }
  }

  #[\Override]
  public function save(DomainCountry $country): CountryId {
    $model = Country::updateOrCreate(
      ['id' => $country->id()?->value()],
      ['name' => $country->name()->value()],
    );

    return CountryId::create($model->id);
  }
}
