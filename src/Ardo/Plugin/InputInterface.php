<?php

namespace WeCamp\Ardo\Plugin;

interface InputInterface
{
    /**
     * @return MessageInterface
     */
    public function poll();
}