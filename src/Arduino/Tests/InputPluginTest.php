<?php

namespace WeCamp\Ardo\Arduino\Tests;

use GuzzleHttp\Client;
use WeCamp\Ardo\Arduino\InputPlugin;
use WeCamp\Ardo\Plugin\MessageInterface;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use WeCamp\Ardo\Slack\Messages\InputMessage;

/**
 * Tests for the InputPlugin class
 *
 * @coversDefaultClass WeCamp\Ardo\Arduino\InputPlugin
 * @covers ::__construct
 * @covers ::<!public>
 */
class InputPluginTest extends \PHPUnit_Framework_TestCase
{
    /** @var InputPlugin */
    private $plugin;
    /** @var Client|Mock */
    private $mockClient;

    /**
     * Get things in order...
     */
    final public function setup()
    {
        /** @var Client|Mock $mockClient */
        $this->mockClient = $this->getMock(Client::class);
        $this->plugin = new InputPlugin($this->mockClient);
    }

    final public function testPluginShouldReceiveHttpClientWhenInstantiated()
    {
        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            '/none given/'
        );

        /** @noinspection PhpParamsInspection */
        new InputPlugin();
    }

    /**
     * @covers ::handleMessage
     */
    final function testPluginShouldComplainWhenNotGivenMessage()
    {
        $plugin = $this->plugin;

        $this->setExpectedExceptionRegExp(
            \PHPUnit_Framework_Error::class,
            '/none given/'
        );

        /** @noinspection PhpParamsInspection */
        $plugin->handleMessage();
    }

    /**
     * @covers ::handleMessage
     */
    final function testPluginShouldOnlyCallArduinoWhenGivenSlackMessage()
    {
        $plugin = $this->plugin;

        /** @var MessageInterface|Mock $mockMessage */
        $mockMessage = $this->getMock(MessageInterface::class);
        $mockMessage->expects($this->never())
            ->method('toString')
        ;

        $plugin->handleMessage($mockMessage);
    }

    /**
     * @covers ::handleMessage
     */
    final function testPluginShouldOnlyCallArduinoWhenGivenSlackMessageIsNotEmpty()
    {
        $plugin = $this->plugin;

        /** @var MessageInterface|Mock $mockMessage */
        $mockMessage = $this->getMockBuilder(InputMessage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mockMessage->expects($this->exactly(1))
            ->method('isEmpty')
            ->willReturn(true);
        ;

        $plugin->handleMessage($mockMessage);
    }

    /**
     * @covers ::handleMessage
     */
    final function testPluginShouldPassMessageToArduinoWhenGivenSlackMessage()
    {
        $plugin = $this->plugin;

        $mockCommand = 'mockCommand';
        $mockParameters = 'AAaAAaAAaa VBBbBBbB';
        $expected = sprintf('%s %s', $mockCommand, $mockParameters);

        $mockClient = $this->mockClient;

        /** @var InputMessage|Mock $mockMessage */
        $mockMessage = $this->getMockBuilder(InputMessage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $mockMessage->expects($this->exactly(1))
            ->method('toString')
            ->willReturn($expected)
        ;

        $mockMessage->expects($this->exactly(1))
            ->method('isEmpty')
            ->willReturn(false);
        ;

        $mockClient->expects($this->exactly(1))
            ->method('__call')
            ->with('get', ['/'.$mockCommand, ['query' => 'params='.$mockParameters]])
        ;

        $plugin->handleMessage($mockMessage);
    }
}

/*EOF*/