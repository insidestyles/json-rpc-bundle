<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

use JMS\Serializer\SerializerInterface as JmsSerializerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JmsSerializer implements SerializerInterface
{
    private $serializer;

    public function __construct(JmsSerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, ?SerializerContextInterface $context = null): string
    {
        return $this->serializer->serialize($data, 'json');
    }
}