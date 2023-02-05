<?php

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler;

use Throwable;

interface ErrorHandlerInterface
{
    public function parse(Throwable $errorObject): array;

    public function isSupported(Throwable $errorObject): bool;
}
