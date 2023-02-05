<?php

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler\ErrorHandlerInterface;
use Throwable;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class ErrorHandlerManager implements ErrorHandlerManagerInterface
{
    /**
     * @var array |ErrorHandlerInterface[]
     */
    private array $handlers = [];

    public function handle(Throwable $e): array
    {
        $handler = $this->getHandler($e);
        if (!$handler instanceof ErrorHandlerInterface) {
            return [
                'code' => Errors::UNKNOWN_ERROR,
                'message' => 'Unknown Error',
                'data' => [],
            ];
        }

        return $handler->parse($e);
    }

    public function addHandler(ErrorHandlerInterface $handler): void
    {
        $this->handlers[$handler::class] = $handler;
    }

    public function getHandler(Throwable $e): ?ErrorHandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isSupported($e)) {
                return $handler;
            }
        }
        return null;
    }
}
