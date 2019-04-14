<?php

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Serializes the given data to the json format.
     */
    public function serialize($data, ?SerializerContextInterface $context = null): string;
}
