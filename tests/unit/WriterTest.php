<?php

namespace Sensorario\Yaphlo\Tests;

use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Config\Config;
use Sensorario\Yaphlo\FileWriterWrapper;
use Sensorario\Yaphlo\ChannelVisibilityChecker;

class WriterTest extends \PHPUnit\Framework\TestCase
{
    private Message $message;

    private FileWriterWrapper $fileWriterWrapper;

    private Config $conf;

    public function setUp(): void
    {
        $this->conf = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->message = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Message::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileWriterWrapper = $this
            ->getMockBuilder(\Sensorario\Yaphlo\FileWriterWrapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->checker = $this
            ->getMockBuilder(\Sensorario\Yaphlo\ChannelVisibilityChecker::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @test */
    public function writeIntoFile(): void
    {
        $this->fileWriterWrapper
            ->expects($this->once())
            ->method('append');

        $this->message
            ->expects($this->once())
            ->method('render');

        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_INFO);

        $this->conf
            ->expects($this->never())
            ->method('enabledChannels');

        $this->checker
            ->expects($this->once())
            ->method('mustChannelBeHidden')
            ->willReturn(false);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->fileWriterWrapper,
            $this->checker,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function doNotWriteIntoFile(): void
    {
        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_FATAL);

        $this->fileWriterWrapper
            ->expects($this->never())
            ->method('append');

        $this->message
            ->expects($this->never())
            ->method('render');

        $this->conf
            ->expects($this->never())
            ->method('enabledChannels');

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(false);

        $writer = new Writer(
            $this->conf,
            $this->fileWriterWrapper,
            $this->checker,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function writerNeverAppendWheneverEnabledChannelsIsEmpty(): void
    {
        $this->fileWriterWrapper
            ->expects($this->never())
            ->method('append');

        $this->message
            ->expects($this->never())
            ->method('render');

        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_INFO);

        $this->conf
            ->expects($this->never())
            ->method('enabledchannels');

        $this->checker
            ->expects($this->once())
            ->method('mustChannelBeHidden')
            ->willReturn(true);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->fileWriterWrapper,
            $this->checker,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function neverAppendIfChannelEnabledIsNotOfMessage(): void
    {
        $this->fileWriterWrapper
            ->expects($this->never())
            ->method('append');

        $this->message
            ->expects($this->never())
            ->method('render');

        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_INFO);

        $this->conf
            ->expects($this->never())
            ->method('enabledChannels');

        $this->checker
            ->expects($this->once())
            ->method('mustChannelBeHidden')
            ->willReturn(true);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->fileWriterWrapper,
            $this->checker,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function appendIfChannelEnabledIsSmaeOfMessage(): void
    {
        $this->fileWriterWrapper
            ->expects($this->once())
            ->method('append');

        $this->message
            ->expects($this->once())
            ->method('render');

        $this->message
            ->expects($this->never())
            ->method('getChannel');

        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_INFO);

        $this->conf
            ->expects($this->never())
            ->method('enabledChannels')
            ->willReturn(['foo']);

        $this->checker
            ->expects($this->once())
            ->method('mustChannelBeHidden')
            ->willReturn(false);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->fileWriterWrapper,
            $this->checker,
        );

        $writer->write($this->message);
    }
}
