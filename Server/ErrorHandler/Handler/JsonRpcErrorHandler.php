<?php

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use Insidestyles\JsonRpcBundle\Exception\JsonRpcErrorInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Throwable;

class JsonRpcErrorHandler implements ErrorHandlerInterface
{
    #[Pure]
    public function parse(Throwable $errorObject): array
    {
        if (!$this->isSupported($errorObject)) {
            return [];
        }
        return [
            'code' => $errorObject->getCode(),
            'message' => $errorObject->getMessage(),
            'data' => [],
        ];
    }

    public function isSupported(Throwable $errorObject): bool
    {
        return $errorObject instanceof JsonRpcErrorInterface;
    }
}