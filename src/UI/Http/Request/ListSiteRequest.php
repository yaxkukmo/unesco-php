<?php

declare(strict_types=1);

namespace App\UI\Http\Request;

final class ListSiteRequest {
  public function __construct(
    public readonly int $page,
    public readonly int $perPage,
    public readonly ?int $country,
    public readonly ?string $category,
  ) {}

  public static function fromHttp(array $query): self {
    return new self(
      page: min(10000, max(1, (int) ($query['page'] ?? 1))),
      perPage: min(100, max(1, (int) ($query['perPage'] ?? 20))),
      country: isset($query['country']) ? min(200, max(1, (int) $query['country'])) : null,
      category: !empty($query['category']) ? substr($query['category'], 0, 255) : null,
    );
  }
}
