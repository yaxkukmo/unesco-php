<?php

declare(strict_types=1);

namespace App\Application\Bus;

use Psr\Container\ContainerInterface;
use InvalidArgumentException;

final class SimpleCommandBus implements CommandBusInterface {
  private array $handlers = [];

  public function __construct(private ContainerInterface $container) {}

  public function register(string $commandClass, string $handlerClass): void {
    $this->handlers[$commandClass] = $handlerClass;
  }

  public function dispatch(object $command): mixed {
    $commandClass = get_class($command);
    if (!isset($this->handlers[$commandClass])) {
      throw new InvalidArgumentException("No handler for {$commandClass}");
    }
    $handler = $this->container->get($this->handlers[$commandClass]);
    return $handler->handle($command);
  }
}
