<?php

namespace Insidestyles\JsonRpcBundle\Api;

use Insidestyles\JsonRpcBundle\Message\HelloWordMessage;
use Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordJsonRpcApiInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWordApi extends AbstractApi implements HelloWordJsonRpcApiInterface
{
    public function helloWorld(string $name)
    {
        $message = new HelloWordMessage($name);

        return $this->handle($message);
    }
}