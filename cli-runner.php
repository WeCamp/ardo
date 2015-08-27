<?php
require 'vendor/autoload.php';
$bot = new WeCamp\Ardo\Bot();
$cli = new WeCamp\Ardo\Cli\Cli(WeCamp\Ardo\Messages\Message::createFromString("hello i am a bot"));
$bot->registerInput($cli);
$bot->registerOutput($cli);

$bot->tick();