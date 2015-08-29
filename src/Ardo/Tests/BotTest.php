<?php
namespace WeCamp\Ardo\Tests;

use PHPUnit_Framework_MockObject_MockObject as Mock;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use WeCamp\Ardo\Bot;
use WeCamp\Ardo\Plugin\InputInterface;
use WeCamp\Ardo\Plugin\MessageInterface;
use WeCamp\Ardo\Plugin\OutputInterface;

/**
 * Test all the Bot!
 */
class BotTest extends \PHPUnit_Framework_TestCase
{
    /** @var Bot */
    private $bot;
    /** @var LoggerInterface|Mock  */
    private $mockLogger;

    /**
     * Create test subject
     */
    final public function setup()
    {
        $this->mockLogger = $this->getMock(LoggerInterface::class);
        $this->bot = new Bot($this->mockLogger);
    }

    function testTicksChecksAllInputs()
    {
        $bot = $this->bot;

        $inputInterface = $this->getMockInput();
        $message = $this->getMockMessage();

        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $bot->registerInput($inputInterface->reveal());
        $bot->tick();
    }

    function testBotIsSendingARecievedMessageToOutput()
    {
        $bot = $this->bot;

        $message = $this->prophesize(MessageInterface::class);

        $inputInterface = $this->prophesize(InputInterface::class);
        $inputInterface->poll()->willReturn($message->reveal())->shouldBeCalled();

        $outputInterface = $this->prophesize(OutputInterface::class);
        $outputInterface->handleMessage($message->reveal())->shouldBeCalled();

        $bot->registerInput($inputInterface->reveal());
        $bot->registerOutput($outputInterface->reveal());

        $bot->tick();
    }

    /**
     * @return InputInterface|ObjectProphecy
     */
    private function getMockInput()
    {
        return $this->prophesize(InputInterface::class);
    }

    /**
     * @return ObjectProphecy
     */
    private function getMockMessage()
    {
        return $this->prophesize(MessageInterface::class);
    }
}
