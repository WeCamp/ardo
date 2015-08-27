<?php
namespace WeCamp\Ardo\Slack\ValueObject;

use WeCamp\Ardo\Slack\Messages\Message\SlackMessage;

class SlackTimestamp
{

    /**
     * @var String
     */
    private $value;

    /**
     * @param $timestamp
     */
    private function __construct($timestamp)
    {
        $this->value = $timestamp;
    }

    /**
     * @param \DateTime $dateTime
     * @param int $counter
     * @return SlackTimestamp
     */
    public static function createFromDatetimeAndCounter(\DateTime $dateTime, $counter)
    {
        return new static(sprintf("%d.%06d", $dateTime->getTimestamp(), $counter));
    }

    /**
     * @param $ts
     * @return SlackTimestamp
     */
    public static function createFromSlackString($ts)
    {
        return new static($ts);
    }

    /**
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }
}