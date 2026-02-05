<?php

namespace App\Controllers;

use App\Services\DetailsSiteService;
use App\HttpResponse;

class DetailsSiteController {

  public function __construct(private DetailsSiteService $service) {}

  public function __invoke(int $id): void
  {
    $result = $this->service->handle($id);
    if (!$result) {
      HttpResponse::json(['success' => false, 'error' => 'Site not found'], 404);
      return;
    }

    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
