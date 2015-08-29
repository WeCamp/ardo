<?php

namespace WeCamp\Ardo\Arduino;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use WeCamp\Ardo\Messages\Message;
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
        $responseMessage = null;

        if ($this->shouldCallArduino($message)) {
            $message = $message->toString();
            $this->log($message);
            $response = $this->callArduino($this->client, $message);

            if ($response instanceof ResponseInterface) {
                $message = json_decode($response->getBody());
                $responseMessage = Message::createFromString((string) $message->return_value);

            }

        }

        return $responseMessage;
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

    private function callArduino(Client $client, $request)
    {
        $response = null;

        $parameters = $this->buildResponse($request);

        if (empty($parameters) === false) {
            $command = array_shift($parameters);
            $parameters = array_shift($parameters);
            $response = $client->get('/' . $command, ['query' => 'params=' . $parameters]);

            $this->log($command, ['response' => $response]);
        }

        return $response;
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    private function shouldCallArduino(MessageInterface $message)
    {
        /* @TODO: Add check for available commands */
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

    private function buildResponse($request)
    {
        $parameters = [];
        $keyword = '';

        $request = trim($request);

        $availableCommands = [
            /* @NOTE: Because the keywords are searched in order, the longer version of similar keywords need to go first */
            'sing a song' => 'play',
            'play a song' => 'play',
            'sing' => 'play',
            'play' => 'play',
            'song' => 'play',
            'temperature' => 'temp',
            'temp' => 'temp',
            'light' => 'light',
            'laugh' => 'play',
        ];

        foreach ($availableCommands as $keyword => $subject) {
            if (strpos($request, $keyword) === 0) {
                array_push($parameters, $subject);
                break;
            }
        }

        if (empty($parameters) === false) {

            if ($keyword === 'laugh') {
                array_push($parameters, 'g g g g g g');
            } else {
                $parameterString = substr($request, strlen($keyword));
                $parameterString = ltrim($parameterString, ': ');
                array_push($parameters, $parameterString);
            }
        }

        return $parameters;
    }
}

/*EOF*/
