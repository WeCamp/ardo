<?php

namespace WeCamp\Ardo\Plugin;

interface OutputInterface
{
    public function handleMessage(MessageInterface $message);
}
