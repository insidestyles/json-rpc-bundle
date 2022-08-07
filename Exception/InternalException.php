<?php
/**
 * User: insidestyles
 * Date: 12.04.19
 * Time: 10:55
 */

namespace Insidestyles\JsonRpcBundle\Exception;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class InternalException extends \Exception implements JsonRpcErrorInterface
{
    protected $message = 'Internal Server Error';
    protected $code = -32099;
}