<?php

namespace Ardo\Cli;

use Ardo\Plugin\InputInterface;
use Ardo\Plugin\MessageInterface;
use Ardo\Plugin\OutputInterface;

class Cli implements InputInterface, OutputInterface
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }
    /**
     * return Message
     */
    public function poll()
    {
        return $this->message;
    }

    public function handleMessage(MessageInterface $message)
    {
        printf('I am handling this: %s', $message->toString());
    }
}