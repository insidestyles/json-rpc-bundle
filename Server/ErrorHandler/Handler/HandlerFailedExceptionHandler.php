<?php

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use Insidestyles\JsonRpcBundle\Server\ErrorHandler\ErrorHandlerManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

class HandlerFailedExceptionHandler implements ErrorHandlerInterface
{
    public function __construct(private readonly ErrorHandlerManagerInterface $errorHandlerManager)
    {
    }

    #[Pure]
    public function parse(Throwable $errorObject): array
    {
        if (!$this->isSupported($errorObject)) {
            return [];
        }

        $previous = $errorObject->getPrevious();

        if ($previous) {
            return $this->errorHandlerManager->handle($previous);
        }

        return [
            'code' => Errors::MESSAGE_BUS_ERROR,
            'message' => 'Handler Message Failed',
            'data' => [],
        ];
    }

    public function isSupported(Throwable $errorObject): bool
    {
        return $errorObject instanceof HandlerFailedException;
    }
}
