<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Site;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\GetSiteDetails\GetSiteDetailsQuery;
use App\UI\Http\Response\HttpResponse;

final class DetailsSiteController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(string $id): void
  {
    $result = $this->queryBus->ask(new GetSiteDetailsQuery($id));

    if ($result === null) {
      HttpResponse::json(['success' => false, 'error' => 'Site not found'], 404);
      return;
    }

    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
