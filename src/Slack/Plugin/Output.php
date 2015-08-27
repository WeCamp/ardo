<?php

namespace WeCamp\Ardo\Slack\Plugin;

use WeCamp\Ardo\Messages\Message;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;
use WeCamp\Ardo\Slack\Service\SlackInterface;

class Output implements OutputInterface
{
    /**
     * @var SlackInterface
     */
    private $slack;

    /**
     * @param SlackInterface $slack
     */
    public function __construct(SlackInterface $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @param MessageInterface $message
     */
    public function handleMessage(MessageInterface $message)
    {
        if ($message->isEmpty() === false) {
            $this->slack->sendMessage(Message::createFromString('Repling to: ' . $message->toString()));
        }
    }
}