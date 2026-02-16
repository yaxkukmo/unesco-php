<?php

declare(strict_types=1);

namespace App\Domain\Site\Repository;

use App\Domain\Site\Entity\Site;
use App\Domain\Site\ValueObject\Id;

interface SiteCommandRepositoryInterface {
  public function findById(Id $id): Site;
  public function save(Site $site): void;
  public function delete(Id $id): void;
}
