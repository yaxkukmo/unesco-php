<?php

declare(strict_types=1);

namespace App\Application\Command\CreateSite;

final class CreateSiteCommand {
  public function __construct(
    public readonly string $name,
    public readonly ?string $description,
    public readonly ?string $externalUrl,
    public readonly ?float $latitude,
    public readonly ?float $longitude,
    public readonly ?int $countryId,
    public readonly array $categoryIds = [],
  ) {}
}
