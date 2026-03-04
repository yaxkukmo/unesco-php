<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Site;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\ListSites\ListSitesQuery;
use App\UI\Http\Response\HttpResponse;

final class ListSiteController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(): void
  {
    $query = new ListSitesQuery(
      page: max(1, (int) ($_GET['page'] ?? 1)),
      perPage: (int) ($_GET['perPage'] ?? 20),
      countryId: isset($_GET['country']) ? (int) $_GET['country'] : null,
      categoryId: isset($_GET['category']) ? (int) $_GET['category'] : null,
    );

    $result = $this->queryBus->ask($query);

    HttpResponse::json([
      'success' => true,
      'data' => $result['data'],
      'meta' => $result['meta'],
    ]);
  }
}
