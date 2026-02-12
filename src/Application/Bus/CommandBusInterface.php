<?php

declare(strict_types=1);

namespace App\Application\Bus;

interface CommandBusInterface {
  public function dispatch(object $command): mixed;
}
