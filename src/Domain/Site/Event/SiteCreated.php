<?php

declare(strict_types=1);

namespace App\Domain\Site\Event;

use App\Domain\Site\ValueObject\Id;
use App\Domain\Site\ValueObject\Name;
use App\Domain\Site\ValueObject\Coordinates;
use DateTimeImmutable;

final class SiteCreated {

  public function __construct(
    public readonly Id $id,
    public readonly Name $name,
    public readonly ?Coordinates $coordinates,
    public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
  ) { }
}
