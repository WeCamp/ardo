<?php

namespace WeCamp\Ardo\Arduino;

use GuzzleHttp\Client;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;
use WeCamp\Ardo\Slack\Messages\InputMessage;

class InputPlugin implements OutputInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    final public function handleMessage(MessageInterface $message)
    {
        if ($this->shouldCallArduino($message)) {
            $this->callArduino($this->client, $message->toString());
        }
    }

    private function callArduino(Client $client, $message)
    {
        list($url, $parameter) = explode(' ', $message, 2);
        $response = $client->request('get', $url, ['params' => $parameter]);

        return $response;
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    private function shouldCallArduino(MessageInterface $message)
    {
        $isEmpty = $message->isEmpty();
        return $message instanceof InputMessage && $isEmpty === false;
    }
}
/*EOF*/
