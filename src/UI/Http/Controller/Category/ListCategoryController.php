<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Category;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\ListCategories\ListCategoriesQuery;
use App\UI\Http\Response\HttpResponse;

final class ListCategoryController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(): void
  {
    $result = $this->queryBus->ask(new ListCategoriesQuery());
    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
