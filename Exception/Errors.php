<?php
/**
 * User: insidestyles
 * Date: 22.04.19
 * Time: 11:57
 */

namespace Insidestyles\JsonRpcBundle\Exception;


/**
 * class Errors
 * @author Fuong <insidestyles@gmail.com>
 */
interface Errors
{
    const APPLICATION_ERROR = -32500;
    const VALIDATION_ERROR = -32501;
    const INTERNAL_ERROR = -32502;
    const REMOTE_OBJECT_CALL_ERROR = -32503;
}
