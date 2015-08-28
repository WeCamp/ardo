<?php
require 'vendor/autoload.php';

use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/* Slack Plugin */
$interactor = new CurlInteractor;
$interactor->setResponseFactory(new SlackResponseFactory);

$slackService = new WeCamp\Ardo\Slack\Service\Slack(
    new Commander(\getenv('SLACK_TOKEN'), $interactor),
    'C09JL3GR0'
);
$sinceService = new \WeCamp\Ardo\Slack\Service\Since('/tmp/since.db');
$slackInput = new WeCamp\Ardo\Slack\Plugin\Input(
    $slackService,
    $sinceService
);
$slackOutput = new WeCamp\Ardo\Slack\Plugin\Output($slackService);

/* Commandline Plugin */
$cli = new WeCamp\Ardo\Cli\Cli(WeCamp\Ardo\Messages\Message::createFromString("hello i am a bot"));

/* Arduino Plugin */
$httpClient = new \GuzzleHttp\Client(['base_uri' => '10.0.0.3',]);
$arduinoPlugin = new \WeCamp\Ardo\Arduino\InputPlugin($httpClient);

/* Logging */
$logger = new Logger('Ardo');
$logger->pushHandler(new StreamHandler('ardoBot.log', Logger::INFO));

/* Feed the Plugins and Logger to Ardo */
$bot = new WeCamp\Ardo\Bot($logger);
$bot->registerInput($slackInput);
//$bot->registerInput($cli);
$bot->registerOutput($cli);
$bot->registerOutput($slackOutput);
$bot->registerOutput($arduinoPlugin);

/* Crank the handle. */
$bot->tick();
