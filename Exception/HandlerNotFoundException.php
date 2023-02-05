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
class HandlerNotFoundException extends \Exception implements JsonRpcErrorInterface
{
    protected $message = 'Handler Not Found';
    protected $code = -32098;
}
