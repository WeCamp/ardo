<?php

namespace WeCamp\Ardo\Slack\Plugin;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Slack\Messages\InputMessage;
use WeCamp\Ardo\Slack\Service\SinceInterface;
use WeCamp\Ardo\Slack\Service\SlackInterface;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

class Input implements InputInterface, LoggerAwareInterface
{

    const KEYWORD = 'ardo';

    /**
     * @var SlackInterface
     */
    private $slack;

    /**
     * @var SinceInterface
     */
    private $since;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SlackInterface $slack
     * @param SinceInterface $since
     */
    public function __construct(SlackInterface $slack, SinceInterface $since)
    {
        $this->slack = $slack;
        $this->since = $since;
    }

    /**
     * @return MessageInterface
     */
    public function poll()
    {
        $messages = $this->slack->getMessages($this->since->getLastTime());
        $returnValue = InputMessage::createFromNothing();
        foreach ($messages as $message) {
            if (
                $returnValue->isEmpty() === true &&
                \strtolower(\substr($message->toString(), 0, \strlen(self::KEYWORD))) == self::KEYWORD
            ) {
                $this->logger->info(self::class . ": " . $message->toString());
                $returnValue = InputMessage::createFromString(
                    \substr(
                        $message->toString(),
                        \strlen(self::KEYWORD)
                    )
                );
                $this->since->update($message->getTimestamp());
            }
        }
        if ($returnValue->isEmpty()) {
            $this->logger->info(self::class . ': No Message');
            $this->since->update(SlackTimestamp::createNow());
        }
        return $returnValue;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


}