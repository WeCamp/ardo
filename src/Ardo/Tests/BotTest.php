<?php
namespace Ardo\Tests;

use Ardo\Bot;
use Ardo\Cli\Cli;
use Ardo\Plugin\InputInterface;
use Ardo\Plugin\MessageInterface;
use Ardo\Plugin\OutputInterface;

class BotTest extends \PHPUnit_Framework_TestCase
{

    function testTicksChecksAllInputs()
    {
        $message = $this->prophesize(MessageInterface::class);

        $inputInterface = $this->prophesize(InputInterface::class);
        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $bot = new \Ardo\Bot();
        $bot->registerInput($inputInterface->reveal());
        $bot->tick();
    }

    /**
     *
     */
    function testBotIsSendingARecievedMessageToOutput()
    {
        $message = $this->prophesize(MessageInterface::class);

        $inputInterface = $this->prophesize(InputInterface::class);
        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $outputInterface = $this->prophesize(OutputInterface::class);
        $outputInterface->handleMessage($message->reveal())->shouldBeCalled();

        $bot = new \Ardo\Bot();
        $bot->registerInput($inputInterface->reveal());
        $bot->registerOutput($outputInterface->reveal());

        $bot->tick();
    }
}
