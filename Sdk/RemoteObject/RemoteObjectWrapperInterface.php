<?php

namespace Insidestyles\JsonRpcBundle\Sdk\RemoteObject;


/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface RemoteObjectWrapperInterface
{
    public function setHttpHeaders(array $headers): void;

    public function setOptions(array $options): void;
}
