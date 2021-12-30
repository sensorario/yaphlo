<?php

namespace Sensorario\Yaphlo\Services;

class ChannelVisibilityCheckerTest extends \PHPUnit\Framework\TestCase
{
    private $message;
    
    private $checker;

    public function setUp(): void
    {
        $this->conf = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Config\Config::class)
            ->getMock();

        $this->message = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Message::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @test */
    public function showMessageDueToChannel()
    {
        $this->message->expects($this->once())
            ->method('getChannel')
            ->willReturn('second channel');

        $this->conf->expects($this->once())
            ->method('enabledChannels')
            ->willReturn(['first channel', 'second channel']);

        $this->checker = new ChannelVisibilityChecker();

        $this->checker->setConfig($this->conf);

        $hide = $this->checker->mustChannelBeHidden($this->message);

        $this->assertFalse($hide);
    }

    /** @test */
    public function hideMessageDueToChannel()
    {
        $this->message->expects($this->once())
            ->method('getChannel')
            ->willReturn('wrong channel');

        $this->conf->expects($this->once())
            ->method('enabledChannels')
            ->willReturn(['first channel', 'second channel']);

        $this->checker = new ChannelVisibilityChecker();

        $this->checker->setConfig($this->conf);

        $hide = $this->checker->mustChannelBeHidden($this->message);

        $this->assertTrue($hide);
    }
}
