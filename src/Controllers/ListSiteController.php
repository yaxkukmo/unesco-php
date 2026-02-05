<?php

namespace App\Controllers;

use App\Filters\ListSiteFilter;
use App\Services\ListSiteService;
use App\HttpResponse;

class ListSiteController {

  public function __construct(private ListSiteService $service) {}

  public function __invoke(): void
  {
    $filter = ListSiteFilter::fromHttp($_GET);
    $results = $this->service->handle($filter);

    HttpResponse::json([
      'success' => true,
      'data' => $results->sites,
      'meta' => [
        'total' => $results->total,
        'page' => $filter->page,
        'per_page' => $filter->perPage,
        'last_page' => (int)ceil($results->total / $filter->perPage),
      ],
    ]);
  }
}
