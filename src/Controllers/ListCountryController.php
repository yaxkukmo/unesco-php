<?php

namespace App\Controllers;

use App\Services\ListCountryService;
use App\HttpResponse;

class ListCountryController {

    public function __construct(private ListCountryService $service) {}

    public function __invoke(): void
    {
      $result = $this->service->handle();
      HttpResponse::json(['success' => true, 'data' => $result->countries]);
    }

}
