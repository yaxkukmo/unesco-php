<?php

declare(strict_types=1);

namespace App\Domain\Site\Enum;

enum Status {
  case Pending;
  case Approved;
  case Rejected;
}
