<?php

namespace WeCamp\Ardo\Plugin;

interface InputInterface extends PluginInterface
{
    /**
     * @return MessageInterface
     */
    public function poll();
}