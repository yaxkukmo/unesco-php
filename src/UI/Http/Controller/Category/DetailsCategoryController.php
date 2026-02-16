<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Category;

use App\Application\Bus\QueryBusInterface;
use App\Application\Query\GetCategoryDetails\GetCategoryDetailsQuery;
use App\UI\Http\Response\HttpResponse;

final class DetailsCategoryController {

  public function __construct(private QueryBusInterface $queryBus) {}

  public function __invoke(int $id): void
  {
    $result = $this->queryBus->ask(new GetCategoryDetailsQuery($id));

    if ($result === null) {
      HttpResponse::json(['success' => false, 'error' => 'Category not found'], 404);
      return;
    }

    HttpResponse::json(['success' => true, 'data' => $result]);
  }
}
