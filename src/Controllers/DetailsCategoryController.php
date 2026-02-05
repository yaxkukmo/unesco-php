<?php

namespace App\Controllers;

use App\Services\DetailsCategoryService;
use App\HttpResponse;

class DetailsCategoryController {

  public function __construct(private DetailsCategoryService $service) {}

  public function __invoke(int $id): void
  {
    $result = $this->service->handle($id);
    if (!$result) {
      HttpResponse::json(['success' => false, 'error' => 'Category not found'], 404);
      return;
    }
    HttpResponse::json(['success' => true,'data' => $result]);
  }
}
