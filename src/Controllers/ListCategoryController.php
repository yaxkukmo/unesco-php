<?php

namespace App\Controllers;

use App\Services\ListCategoryService;
use App\HttpResponse;

class ListCategoryController {

    public function __construct(private ListCategoryService $service) {}

    public function __invoke(): void
    {
        $result = $this->service->handle();
        HttpResponse::json(['success' => true, 'data' => $result->categories]);
    }

}
