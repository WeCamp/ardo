<?php

namespace Ardo\Plugin;

interface OutputInterface
{
    public function handleMessage(MessageInterface $message);
}