<?php

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface SerializerContextInterface
{
    public function setGroups(array $groups);

    public function getGroups(): array;
}
