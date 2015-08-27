<?php

namespace WeCamp\Ardo\Plugin;


interface MessageInterface
{

    /**
     * @return Boolean
     */
    public function isEmpty();

    /**
     * @return string
     */
    public function toString();
}
