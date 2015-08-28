<?php

namespace WeCamp\Ardo\Slack\Service;

use Frlnc\Slack\Core\Commander;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Slack\Messages\SlackMessage;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

class Slack implements SlackInterface
{
    /**
     * @var Commander
     */
    private $commander;

    /**
     * @var string
     */
    private $channel;

    /**
     * @param Commander $commander
     * @param $channel
     */
    public function __construct(Commander $commander, $channel)
    {
        $this->commander = $commander;
        $this->channel = $channel;
    }

    /**
     * @param MessageInterface $message
     */
    public function sendMessage(MessageInterface $message)
    {
        $this->commander->execute('chat.postMessage', [
            'channel' => $this->channel,
            'text'    => $message->toString(),
            'username'=> 'ardo',
        ]);
    }

    /**
     * @param SlackTimestamp $slackTimestamp
     * @return SlackMessage[]
     */
    public function getMessages(SlackTimestamp $slackTimestamp)
    {
        $response = $this->commander->execute('channels.history', [
            'channel' => $this->channel,
            'oldest'    => $slackTimestamp->getValue()
        ]);
        $responses = [];
        $responseBody = $response->getBody();
        if (isset($responseBody['messages'])) {
            foreach (['messages'] as $message) {
                \array_push($responses,
                    new SlackMessage(
                        $message['text'],
                        SlackTimestamp::createFromSlackString($message['ts'])
                    )
                );
            }
        }
        return $responses;
    }
}