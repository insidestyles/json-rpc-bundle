<?php
namespace Insidestyles\JsonRpcBundle\Exception;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class RemoteObjectCallException extends \Exception implements JsonRpcErrorInterface
{
    protected $message = 'Remote Object Error';
    protected $code = Errors::REMOTE_OBJECT_CALL_ERROR;
}
