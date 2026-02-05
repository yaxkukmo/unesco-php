<?php

namespace App\Controllers;

use App\Services\DetailsCountryService;
use App\HttpResponse;


class DetailsCountryController {

  public function __construct(private DetailsCountryService $service) {}

  public function __invoke(int $id): void
  {
    $result = $this->service->handle($id);
    if (!$result) {
      HttpResponse::json(['success' => false,'error' => 'Country not found'], 404);
      return;
    }

    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
