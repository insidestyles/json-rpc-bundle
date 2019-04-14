<?php

namespace Insidestyles\JsonRpcBundle\Api;

use Insidestyles\JsonRpcBundle\Message\HelloWordMessage;
use Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordApiInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWordApi extends AbstractApi implements HelloWordApiInterface
{
    public function helloWorld(string $name)
    {
        $message = new HelloWordMessage($name);

        return $this->handle($message);
    }
}