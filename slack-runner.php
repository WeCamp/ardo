<?php
require 'vendor/autoload.php';

use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

$interactor = new CurlInteractor;
$interactor->setResponseFactory(new SlackResponseFactory);

$slackService = new WeCamp\Ardo\Slack\Service\Slack(
    new Commander(\getenv('SLACK_TOKEN'), $interactor),
    'C09JL3GR0'
);
$sinceService = new \WeCamp\Ardo\Slack\Service\Since('/tmp/since.db');

$bot = new WeCamp\Ardo\Bot();
$cli = new WeCamp\Ardo\Cli\Cli(WeCamp\Ardo\Messages\Message::createFromString("hello i am a bot"));
$slackInput = new WeCamp\Ardo\Slack\Plugin\Input(
    $slackService,
    $sinceService
);
$slackOutput = new WeCamp\Ardo\Slack\Plugin\Output($slackService);
$bot->registerInput($slackInput);
//$bot->registerInput($cli);
$bot->registerOutput($cli);

$bot->registerOutput($slackOutput);
$bot->tick();
