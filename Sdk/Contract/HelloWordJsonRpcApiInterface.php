<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Sdk\Contract;


/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface HelloWordJsonRpcApiInterface extends JsonRpcApiInterface
{
    public function helloWorld(string $name);
}