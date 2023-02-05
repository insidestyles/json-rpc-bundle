<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

use JetBrains\PhpStorm\Pure;
use JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface as JmsSerializerInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JmsSerializer implements SerializerInterface
{
    #[Pure]
    public function __construct(private readonly JmsSerializerInterface $serializer, private readonly SerializationContextFactoryInterface $contextFactory = new DefaultSerializationContextFactory())
    {
    }

    public function serialize($data, ?SerializerContextInterface $context = null): string
    {
        $jmsContext = $this->contextFactory->createSerializationContext();
        if ($context && !empty($context->getGroups())) {
            $jmsContext->setGroups($context->getGroups());
        }

        return $this->serializer->serialize($data, 'json', $jmsContext);
    }
}
