<?php

namespace WeCamp\Ardo\Messages;

use WeCamp\Ardo\Plugin\MessageInterface;
use Assert\Assertion;

class Message implements MessageInterface
{
    /**
     * @return Message
     */
    public static function createFromNothing()
    {
        return new static('');
    }

    /**
     * @param $message
     * @return Message
     */
    public static function createFromString($message)
    {
        Assertion::string($message);
        return new static($message);
    }

    private function __construct($message)
    {
        $this->message = (string) $message;
    }

    /**
     * @return Boolean
     */
    public function isEmpty()
    {
        return empty($this->message);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->message;
    }
}