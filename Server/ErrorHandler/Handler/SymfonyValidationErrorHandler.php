<?php

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Throwable;

class SymfonyValidationErrorHandler implements ErrorHandlerInterface
{
    #[Pure]
    public function parse(Throwable $errorObject): array
    {
        if (!$this->isSupported($errorObject)) {
            return [];
        }
        return [
            'code' => Errors::VALIDATION_ERROR,
            'message' => 'Validation Error',
            'data' => $errorObject->getViolations(),
        ];
    }

    public function isSupported(Throwable $errorObject): bool
    {
        return $errorObject instanceof ValidationFailedException;
    }
}
