<?php

namespace App\Filters;

interface FilterInterface {
  public static function fromHttp(array $query): self;
}
