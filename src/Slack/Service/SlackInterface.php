<?php

namespace WeCamp\Ardo\Slack\Service;


use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Slack\Messages\SlackMessage;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

interface SlackInterface
{
    /**
     * @param MessageInterface $message
     */
    public function sendMessage(MessageInterface $message);

    /**
     * @param SlackTimestamp $slackTimestamp
     * @return SlackMessage[]
     */
    public function getMessages(SlackTimestamp $slackTimestamp);
}