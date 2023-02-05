<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class DefaultSerializer implements SerializerInterface
{
    public function serialize($data, ?SerializerContextInterface $context = null): string
    {
        return json_encode((array) $data, JSON_THROW_ON_ERROR);
    }
}
