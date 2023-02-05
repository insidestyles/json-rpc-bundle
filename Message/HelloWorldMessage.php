<?php

namespace Insidestyles\JsonRpcBundle\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class HelloWorldMessage
{
    public function __construct(
        /**
         * @Assert\NotBlank()
         */
        private readonly string $message
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
