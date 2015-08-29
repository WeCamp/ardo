<?php
require 'vendor/autoload.php';

use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

use GuzzleHttp\Client;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use WeCamp\Ardo\Arduino\InputPlugin;
use WeCamp\Ardo\Bot;
use WeCamp\Ardo\Cli\Cli;
use WeCamp\Ardo\Messages\Message;
use WeCamp\Ardo\Slack\Plugin\Input;
use WeCamp\Ardo\Slack\Plugin\Output;
use WeCamp\Ardo\Slack\Service\Since;
use WeCamp\Ardo\Slack\Service\Slack;

/* Slack Plugin */
$interactor = new CurlInteractor();
$interactor->setResponseFactory(new SlackResponseFactory);

$slackService = new Slack(new Commander(\getenv('SLACK_TOKEN'), $interactor), 'C09JL3GR0');
$sinceService = new Since('/tmp/since.db');

var_dump($sinceService->getLastTime()->getTime());

$slackInput   = new Input($slackService, $sinceService);
$slackOutput  = new Output($slackService);

/* Commandline Plugin */
$cli = new Cli(Message::createFromString('Hello World, I am a bot!'));

/* Arduino Plugin */
$httpClient    = new Client(['base_uri' => 'http://10.0.0.3']);
$arduinoPlugin = new InputPlugin($httpClient);

/* Logging */
$logger = new Logger('Ardo');
$logger->pushHandler(new StreamHandler('ardoBot.log', Logger::INFO));

/* Feed the Plugins and Logger to Ardo */
$bot = new Bot($logger);
$bot->registerInput($slackInput);
$bot->registerOutput($cli);
$bot->registerOutput($slackOutput);
$bot->registerOutput($arduinoPlugin);

/* Crank the handle. */
$bot->tick();
