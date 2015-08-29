<?php

namespace WeCamp\Ardo\Plugin;

interface OutputInterface extends PluginInterface
{
    /**
     * @param MessageInterface $message
     * @return MessageInterface|null
     */
    public function handleMessage(MessageInterface $message);
}
