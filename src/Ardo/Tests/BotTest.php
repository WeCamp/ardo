<?php
namespace WeCamp\Ardo\Tests;

use WeCamp\Ardo\Bot;
use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;

class BotTest extends \PHPUnit_Framework_TestCase
{

    function testTicksChecksAllInputs()
    {
        $message = $this->prophesize(MessageInterface::class);

        $inputInterface = $this->prophesize(InputInterface::class);
        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $bot = new Bot();
        $bot->registerInput($inputInterface->reveal());
        $bot->tick();
    }

    function testBotIsSendingARecievedMessageToOutput()
    {
        $message = $this->prophesize(MessageInterface::class);

        $inputInterface = $this->prophesize(InputInterface::class);
        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $outputInterface = $this->prophesize(OutputInterface::class);
        $outputInterface->handleMessage($message->reveal())->shouldBeCalled();

        $bot = new Bot();
        $bot->registerInput($inputInterface->reveal());
        $bot->registerOutput($outputInterface->reveal());

        $bot->tick();
    }
}
