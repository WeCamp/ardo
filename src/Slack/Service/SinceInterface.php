<?php
/**
 * Created by IntelliJ IDEA.
 * User: jerryverhoef
 * Date: 27/08/15
 * Time: 21:39
 */

namespace WeCamp\Ardo\Slack\Service;


use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

interface SinceInterface
{

    /**
     * @param SlackTimestamp $time
     */
    public function update(SlackTimestamp $time);

    /**
     * @return SlackTimestamp
     */
    public function getLastTime();
}