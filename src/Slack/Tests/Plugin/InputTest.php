<?php
namespace WeCamp\Ardo\Slack\Plugin;

use PHPUnit_Framework_MockObject_MockObject as Mock;

use Psr\Log\LoggerInterface;

use WeCamp\Ardo\Slack\Messages\InputMessage;
use WeCamp\Ardo\Slack\Messages\SlackMessage;
use WeCamp\Ardo\Slack\Service\SinceInterface;
use WeCamp\Ardo\Slack\Service\SlackInterface;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

/**
 * Tests for the Slack Input Class
 */
class InputTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoggerInterface|Mock */
    private $mockLogger;
    /** @var SinceInterface|Mock */
    private $mockSince;
    /** @var SlackInterface|Mock */
    private $mockSlack;
    /** @var Input */
    private $input;

    /**
     * Get things in order...
     */
    final public function setup()
    {
        $this->mockSlack = $this->getMock(SlackInterface::class);
        $this->mockSince = $this->getMock(SinceInterface::class);

        $this->input = new Input($this->mockSlack, $this->mockSince);

        $this->mockLogger = $this->getMock(LoggerInterface::class);
        $this->input->setLogger($this->mockLogger);
    }

    public function testPollIsReturningAnEmptyMessageWhenThereIsNothingToDo()
    {
        $this->mockSlack->expects(self::once())
            ->method('getMessages')
            ->willReturn([]);

        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );

        $this->mockSince->expects(self::once())
            ->method('getLastTime')
            ->willReturn($slackTimestamp);

        $message = $this->input->poll();

        self::assertInstanceOf(InputMessage::class, $message);
        self::assertTrue($message->isEmpty());
    }

    public function testPollOnlyRespondingToKeyWordMessages()
    {
        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );

        $this->mockSince->expects(self::once())
            ->method('getLastTime')
            ->willReturn($slackTimestamp);

        $this->mockSlack->expects(self::once())
            ->method('getMessages')
            ->willReturn([
                new SlackMessage('doesnot respond to', $slackTimestamp)
            ]);

        $message = $this->input->poll();

        self::assertInstanceOf(InputMessage::class, $message);
        self::assertTrue($message->isEmpty());
    }

    public function testPollRespondsToKeyWordMessage()
    {
        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );

        $this->mockSince->expects(self::once())
            ->method('getLastTime')
            ->willReturn($slackTimestamp);

        $this->mockSlack->expects(self::once())
            ->method('getMessages')
            ->willReturn([
                new SlackMessage(Input::KEYWORD . ' does respond to', $slackTimestamp)
            ]);

        $message = $this->input->poll();

        self::assertInstanceOf(InputMessage::class, $message);
        self::assertFalse($message->isEmpty());
        self::assertEquals(' does respond to', $message->toString());
    }
}
