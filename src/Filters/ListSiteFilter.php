<?php

namespace App\Filters;

final class ListSiteFilter implements FilterInterface {
  public function __construct(
    public int $page,
    public int $perPage,
    public ?int $country,
    public ?string $category
  ) {}

  public static function fromHttp(array $query): self {
    return new self(
      page: min(10000, max(1, (int)$query['page'] ?? 1)),
      perPage: min(100, max(1, (int)($query['perPage'] ?? 20))),
      country: min(200, max(1, (int)($query['country'] ?? 26))),
      category: !empty($query['category']) ? substr($query['category'], 0, 255) : null
    );
  }
}
