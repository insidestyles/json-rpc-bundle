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
    public const APPLICATION_ERROR = -32500;
    public const VALIDATION_ERROR = -32501;
    public const INTERNAL_ERROR = -32502;
    public const REMOTE_OBJECT_CALL_ERROR = -32503;
    public const MESSAGE_BUS_ERROR = -32504;
    public const UNKNOWN_ERROR = -32000;
}
