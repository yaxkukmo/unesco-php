<?php

declare(strict_types=1);

namespace App\Application\Command\CreateSite;

use App\Domain\Site\Entity\Site;
use App\Domain\Site\ValueObject\Id;
use App\Domain\Site\ValueObject\Name;
use App\Domain\Site\ValueObject\Description;
use App\Domain\Site\ValueObject\ExternalUrl;
use App\Domain\Site\ValueObject\Coordinates;
use App\Domain\Site\Repository\SiteCommandRepositoryInterface;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Category\ValueObject\CategoryId;

final class CreateSiteHandler {
  public function __construct(
    private SiteCommandRepositoryInterface $siteRepository,
  ) {}

  public function handle(CreateSiteCommand $command): Id {
    $coordinates = ($command->latitude !== null && $command->longitude !== null)
      ? Coordinates::create($command->latitude, $command->longitude)
      : null;

    $site = Site::create(
      Name::create($command->name),
      $command->description !== null ? Description::create($command->description) : null,
      $command->externalUrl !== null ? ExternalUrl::create($command->externalUrl) : null,
      $coordinates,
      $command->countryId !== null ? CountryId::create($command->countryId) : null,
    );

    foreach ($command->categoryIds as $id) {
      $site->attachCategory(CategoryId::create($id));
    }

    $this->siteRepository->save($site);

    return $site->id();
  }
}
