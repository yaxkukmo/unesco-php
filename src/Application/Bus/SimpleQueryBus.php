<?php

declare(strict_types=1);

namespace App\Application\Bus;

use Psr\Container\ContainerInterface;
use InvalidArgumentException;

final class SimpleQueryBus implements QueryBusInterface {
  private array $handlers = [];

  public function __construct(private ContainerInterface $container) {}

  public function register(string $queryClass, string $handlerClass): void {
    $this->handlers[$queryClass] = $handlerClass;
  }

  public function ask(object $query): mixed {
    $queryClass = get_class($query);
    if (!isset($this->handlers[$queryClass])) {
      throw new InvalidArgumentException("No handler for {$queryClass}");
    }
    $handler = $this->container->get($this->handlers[$queryClass]);
    return $handler->handle($query);
  }
}
