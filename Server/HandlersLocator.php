<?php

namespace Insidestyles\JsonRpcBundle\Server;

use Insidestyles\JsonRpcBundle\Server\Handler\JsonRpcHandlerInterface;
use Insidestyles\JsonRpcBundle\Exception\HandlerNotFoundException;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class HandlersLocator implements HandlersLocatorInterface
{
    private array $handlers = [];

    public function __construct()
    {
    }

    public function addHandler(JsonRpcHandlerInterface $handler, $key): void
    {
        $this->handlers[$key] = $handler;
    }

    public function getHandler(string $key): JsonRpcHandlerInterface
    {
        if (isset($this->handlers[$key])) {
            return $this->handlers[$key];
        }

        throw new HandlerNotfoundException();
    }
}
