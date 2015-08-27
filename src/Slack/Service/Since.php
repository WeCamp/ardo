<?php

namespace WeCamp\Ardo\Slack\Service;


use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

class Since implements SinceInterface
{

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var SlackTimestamp
     */
    private $since;

    /**
     * @param $filepath
     */
    public function __construct($filepath)
    {
        $this->filepath= $filepath;
        $this->since = $this->createSinceFromTimestamp(file_get_contents($this->filepath));
    }


    public function update(SlackTimestamp $time)
    {
        $this->since = $time;
        \file_put_contents($this->filepath, $time->getValue());
    }

    /**
     * @return SlackTimestamp
     */
    public function getLastTime()
    {
        return $this->since;
    }

    /**
     * @param $timestamp
     * @return SlackTimestamp
     */
    private function createSinceFromTimestamp($timestamp)
    {
        return SlackTimestamp::createFromSlackString($timestamp);
    }
}