<?php
/**
 * User: insidestyles
 * Date: 12.04.19
 * Time: 10:39
 */

namespace Insidestyles\JsonRpcBundle\Server\ErrorHandler;

use Insidestyles\JsonRpcBundle\Server\ErrorHandler\Handler\ErrorHandlerInterface;
use Throwable;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface ErrorHandlerManagerInterface
{

    public function handle(Throwable $e): array;

    /**
     * @param ErrorHandlerInterface $handler
     */
    public function addHandler(ErrorHandlerInterface $handler): void;

    /**
     * @param Throwable $e
     * @return ?ErrorHandlerInterface
     */
    public function getHandler(Throwable $e): ?ErrorHandlerInterface;
}