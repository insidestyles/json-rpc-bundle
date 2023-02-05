<?php

namespace Insidestyles\JsonRpcBundle\Api\MessageBus;

use Insidestyles\JsonRpcBundle\Message\HelloWorldMessage;
use Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordJsonRpcApiInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWorldApi extends AbstractApi implements HelloWordJsonRpcApiInterface
{
    public function helloWorld(string $name): mixed
    {
        $message = new HelloWorldMessage($name);

        return $this->handle($message);
    }
}
