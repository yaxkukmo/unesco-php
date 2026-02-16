<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Site;

use App\Application\Bus\CommandBusInterface;
use App\Application\Command\CreateSite\CreateSiteCommand;
use App\UI\Http\Response\HttpResponse;

final class CreateSiteController {

  public function __construct(private CommandBusInterface $commandBus) {}

  public function __invoke(): void
  {
    $body = json_decode(file_get_contents('php://input'), true);

    if (empty($body['name'])) {
      HttpResponse::json(['success' => false, 'error' => 'Name is required'], 400);
      return;
    }

    $command = new CreateSiteCommand(
      name: $body['name'],
      description: $body['description'] ?? null,
      externalUrl: $body['external_url'] ?? null,
      latitude: isset($body['latitude']) ? (float) $body['latitude'] : null,
      longitude: isset($body['longitude']) ? (float) $body['longitude'] : null,
      countryId: isset($body['country_id']) ? (int) $body['country_id'] : null,
      categoryIds: $body['category_ids'] ?? [],
    );

    $id = $this->commandBus->dispatch($command);

    HttpResponse::json(['success' => true, 'data' => ['id' => $id->value()]], 201);
  }
}
