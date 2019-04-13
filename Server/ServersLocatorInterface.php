<?php
/**
 * User: insidestyles
 * Date: 12.04.19
 * Time: 10:39
 */

namespace Insidestyles\JsonRpcBundle\Server;


use Insidestyles\JsonRpcBundle\Exception\ServerNotFoundException;
use Insidestyles\JsonRpcBundle\Server\Handler\JsonRpcServerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface ServersLocatorInterface
{

    /**
     * @param JsonRpcServerInterface $server
     * @param $key
     */
    public function addServer(JsonRpcServerInterface $server, $key): void;

    /**
     * @param string $key
     * @return JsonRpcServerInterface
     * @throws ServerNotFoundException
     */
    public function getServer(string $key): JsonRpcServerInterface;
}