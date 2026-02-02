<?php

namespace App\Results;

class ListSiteResult {
  public function __construct(public array $sites, public int $total) {} 
}
