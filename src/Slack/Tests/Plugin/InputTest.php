<?php
namespace WeCamp\Ardo\Slack\Plugin;

use WeCamp\Ardo\Slack\Messages\InputMessage;
use WeCamp\Ardo\Slack\Messages\SlackMessage;
use WeCamp\Ardo\Slack\Service\Slack;
use WeCamp\Ardo\Slack\ValueObject\SlackTimestamp;

class InputTest extends \PHPUnit_Framework_TestCase
{

    public function testPollIsReturningAnEmptyMessageWhenThereIsNothingToDo()
    {
        /** @var Slack|\PHPUnit_Framework_MockObject_MockObject $slack */
        $slack = $this->getMockBuilder(Slack::class)
            ->disableOriginalConstructor()
            ->getMock();
        $slack->expects(self::once())
            ->method('getMessages')
            ->willReturn([]);

        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );
        $input = new Input($slack, $slackTimestamp);
        $message = $input->poll();
        self::assertInstanceOf(InputMessage::class, $message);
        self::assertTrue($message->isEmpty());
    }

    public function testPollOnlyRespondingToKeyWordMessages()
    {
        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );

        /** @var Slack|\PHPUnit_Framework_MockObject_MockObject $slack */
        $slack = $this->getMockBuilder(Slack::class)
            ->disableOriginalConstructor()
            ->getMock();
        $slack->expects(self::once())
            ->method('getMessages')
            ->willReturn([
                new SlackMessage('doesnot respond to', $slackTimestamp)
            ]);

        $input = new Input($slack, $slackTimestamp);
        $message = $input->poll();
        self::assertInstanceOf(InputMessage::class, $message);
        self::assertTrue($message->isEmpty());
    }

    public function testPollRespondsToKeyWordMessage()
    {
        $slackTimestamp = SlackTimestamp::createFromDatetimeAndCounter(
            new \DateTime('-1 hour'),
            0
        );

        /** @var Slack|\PHPUnit_Framework_MockObject_MockObject $slack */
        $slack = $this->getMockBuilder(Slack::class)
            ->disableOriginalConstructor()
            ->getMock();
        $slack->expects(self::once())
            ->method('getMessages')
            ->willReturn([
                new SlackMessage(Input::KEYWORD . ' does respond to', $slackTimestamp)
            ]);

        $input = new Input($slack, $slackTimestamp);
        $message = $input->poll();
        self::assertInstanceOf(InputMessage::class, $message);
        self::assertFalse($message->isEmpty());
        self::assertEquals(' does respond to', $message->toString());
    }
}
