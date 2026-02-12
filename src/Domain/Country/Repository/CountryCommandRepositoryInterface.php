<?php

declare(strict_types=1);

namespace App\Domain\Country\Repository;

use App\Domain\Country\Entity\Country;
use App\Domain\Country\ValueObject\CountryId;

interface CountryCommandRepositoryInterface {
  public function findById(CountryId $id): Country;
  public function save(Country $country): CountryId;
  public function delete(CountryId $id): void;
}
