<?php

namespace Insidestyles\JsonRpcBundle\Api;

use Insidestyles\JsonRpcBundle\Message\HelloWordMessage;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class HelloWordApi extends AbstractApi
{
    /**
     * @inheritdoc
     */
    public function helloWorld(string $name)
    {
        $message = new HelloWordMessage($name);

        return $this->handle($message);
    }
}