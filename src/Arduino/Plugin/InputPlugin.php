<?php

namespace WeCamp\Ardo\Arduino;

use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;
use WeCamp\Ardo\Slack\Messages\InputMessage;

/**
 * Plugin to send messages to the Arduino
 */
class InputPlugin implements OutputInterface, LoggerAwareInterface
{
    /** @var Client */
    private $client;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    final public function handleMessage(MessageInterface $message)
    {
        if ($this->shouldCallArduino($message)) {
            $this->log($message->toString());
            $this->callArduino($this->client, $message->toString());
        }
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    final public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function callArduino(Client $client, $message)
    {
        list($url, $parameter) = explode(' ', trim($message), 2);
        $response = $client->get('/' . $url, ['query' => 'params=' . $parameter]);

        $this->log($url, ['response' => $response]);

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

    /**
     * @param $message
     * @param $context
     */
    private function log($message, $context = [])
    {
        if ($this->logger instanceof LoggerInterface) {
            $context['class'] = self::class;
            $this->logger->info($message, $context);
        }
    }
}

/*EOF*/
