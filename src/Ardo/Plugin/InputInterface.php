<?php

namespace Ardo\Plugin;

interface InputInterface
{
    /**
     * @return MessageInterface
     */
    public function poll();
}