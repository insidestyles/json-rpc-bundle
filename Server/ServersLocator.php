<?php

namespace Insidestyles\JsonRpcBundle\Server;

use Insidestyles\JsonRpcBundle\Server\Handler\JsonRpcServerInterface;
use Insidestyles\JsonRpcBundle\Exception\ServerNotFoundException;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class ServersLocator implements ServersLocatorInterface
{
    private $servers = [];

    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function addServer(JsonRpcServerInterface $server, $key): void
    {
        $this->servers[$key] = $server;
    }

    /**
     * @inheritdoc
     */
    public function getServer(string $key): JsonRpcServerInterface
    {
        if (isset($this->servers[$key])) {
            return $this->servers[$key];
        }

        throw new ServerNotfoundException();
    }
}
