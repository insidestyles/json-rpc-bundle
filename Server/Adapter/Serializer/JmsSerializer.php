<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

use JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface as JmsSerializerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JmsSerializer implements SerializerInterface
{
    private $serializer;
    private $contextFactory;

    public function __construct(JmsSerializerInterface $serializer, ?SerializationContextFactoryInterface $contextFactory = null)
    {
        $this->serializer = $serializer;
        $this->contextFactory = $contextFactory ?? new DefaultSerializationContextFactory();
    }

    public function serialize($data, ?SerializerContextInterface $context = null): string
    {
        $jmsContext = $this->contextFactory->createSerializationContext();
        $jmsContext->setGroups($context && !empty($context->getGroups()) ? $context->getGroups() : ['default' => []]);

        return $this->serializer->serialize($data, 'json', $jmsContext);
    }
}