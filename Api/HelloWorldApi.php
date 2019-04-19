<?php

namespace Insidestyles\JsonRpcBundle\Api;

use Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordJsonRpcApiInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWorldApi implements HelloWordJsonRpcApiInterface
{
    public function helloWorld(string $name)
    {
        return sprintf('Hello %s', $name);
    }
}