<?php
/**
 * User: insidestyles
 * Date: 11.04.19
 * Time: 17:32
 */

namespace Insidestyles\JsonRpcBundle\Handler;

use Insidestyles\JsonRpcBundle\Message\HelloWorldMessage;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class HelloWorldHandler
{
    public function __invoke(HelloWorldMessage $message)
    {
        return sprintf('Hello %s', $message->getMessage());
    }
}