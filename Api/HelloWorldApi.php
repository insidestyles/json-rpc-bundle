<?php

namespace Insidestyles\JsonRpcBundle\Api;

use Insidestyles\JsonRpcBundle\Message\HelloWorldMessage;
use Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordJsonRpcApiInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWorldApi extends AbstractApi implements HelloWordJsonRpcApiInterface
{
    public function helloWorld(string $name)
    {
        $message = new HelloWorldMessage($name);

        return $this->handle($message);
    }
}