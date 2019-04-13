<?php

namespace Insidestyles\JsonRpcBundle\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class HelloWordMessage
{
    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}