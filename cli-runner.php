<?php
require 'vendor/autoload.php';
$bot = new \Ardo\Bot();
$cli = new \Ardo\Cli\Cli(\Ardo\Messages\Message::createFromString("hello i am a bot"));
$bot->registerInput($cli);
$bot->registerOutput($cli);

$bot->tick();