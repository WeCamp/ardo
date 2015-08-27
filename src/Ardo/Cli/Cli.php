<?php

namespace WeCamp\Ardo\Cli;

use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;

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
        printf("I am handling this: %s\n", $message->toString());
    }
}