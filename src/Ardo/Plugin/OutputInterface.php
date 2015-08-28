<?php

namespace WeCamp\Ardo\Plugin;

interface OutputInterface extends PluginInterface
{
    public function handleMessage(MessageInterface $message);
}
