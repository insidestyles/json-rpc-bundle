<?php
/**
 * User: insidestyles
 * Date: 12.04.19
 * Time: 10:39
 */

namespace Insidestyles\JsonRpcBundle\Server;

use Insidestyles\JsonRpcBundle\Server\Handler\JsonRpcHandlerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface HandlersLocatorInterface
{

    /**
     * @param JsonRpcHandlerInterface $handler
     * @param $key
     */
    public function addHandler(JsonRpcHandlerInterface $handler, $key): void;

    /**
     * @param string $key
     * @return JsonRpcHandlerInterface
     */
    public function getHandler(string $key): JsonRpcHandlerInterface;
}