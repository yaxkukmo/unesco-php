<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Country;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\ListCountries\ListCountriesQuery;
use App\UI\Http\Response\HttpResponse;

final class ListCountryController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(): void
  {
    $result = $this->queryBus->ask(new ListCountriesQuery());
    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
