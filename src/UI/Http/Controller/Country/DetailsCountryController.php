<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Country;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\GetCountryDetails\GetCountryDetailsQuery;
use App\UI\Http\Response\HttpResponse;

final class DetailsCountryController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(int $id): void
  {
    $result = $this->queryBus->ask(new GetCountryDetailsQuery($id));

    if ($result === null) {
      HttpResponse::json(['success' => false, 'error' => 'Country not found'], 404);
      return;
    }

    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
