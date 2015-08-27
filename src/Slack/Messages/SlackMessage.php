<?php
namespace WeCamp\Ardo\Slack\Messages;

use Assert\Assertion;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

class SlackMessage implements MessageInterface
{
    /**
     * @var String
     */
    private $text;

    /**
     * @var SlackTimestamp
     */
    private $timestamp;

    /**
     * @param $text
     * @param SlackTimestamp $timestamp
     */
    public function __construct($text, SlackTimestamp $timestamp)
    {
        Assertion::string($text);

        $this->text = $text;
        $this->timestamp = $timestamp;
    }

    /**
     * @return Boolean
     */
    public function isEmpty()
    {
        return empty($this->text);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->text;
    }

    /**
     * @return SlackTimestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}