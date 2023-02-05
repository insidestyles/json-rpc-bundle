<?php

namespace Insidestyles\JsonRpcBundle\Api\MessageBus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
abstract class AbstractApi
{
    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    /**
     *
     * @param object|Envelope $message The message or the message pre-wrapped in an envelope
     *
     * @return mixed The handler returned value
     */
    protected function handle(object $message): mixed
    {
        $envelope = $this->messageBus->dispatch($message);
        $handledStamp = $envelope->last(HandledStamp::class);

        return ($handledStamp instanceof HandledStamp) ? $handledStamp->getResult() : null;
    }
}
