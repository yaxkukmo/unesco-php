<?php

declare(strict_types=1);

namespace App\Domain\Country\Repository;

interface CountryCommandRepositoryInterface {
  public function findById(CountryId $id): ?Country;
  public function save(Country $country): CountryId;
  public function delete(CountryId $id): void;
}
