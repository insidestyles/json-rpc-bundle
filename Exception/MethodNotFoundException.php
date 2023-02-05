<?php

namespace Insidestyles\JsonRpcBundle\Exception;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class MethodNotFoundException extends \Exception implements JsonRpcErrorInterface
{
    protected $message = 'Invalid Method';
    protected $code = -32601;
}
