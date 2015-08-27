<?php
namespace WeCamp\Ardo\Slack\ValueObject;

use Assert\Assertion;

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

    public static function createNow()
    {
        return new static(sprintf("%d.000000", time()));
    }

    public function getTime()
    {
        list($timestamp, ) = explode('.', $this->getValue());
        return $timestamp;
    }

    /**
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }
}