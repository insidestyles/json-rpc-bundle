<?php

namespace Insidestyles\JsonRpcBundle\Sdk\RemoteObject;


/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface RemoteObjectWrapperInterface
{
    public function setHttpHeaders(array $headers);

    public function setOptions(array $options);
}
