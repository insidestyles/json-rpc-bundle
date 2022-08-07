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
class ValidationException extends \Exception implements JsonRpcErrorInterface
{
    protected $message = 'Validation Error';
    protected $code = -32097;
}