<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class SymfonySerializer implements SerializerInterface
{
    private $serializer;

    public function __construct(SymfonySerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, ?SerializerContextInterface $context = null): string
    {
        return $this->serializer->serialize($data, 'json', $context ? $context->getGroups() : []);
    }
}