<?php

namespace Sensorario\Yaphlo\Tests;

use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\Message;

class WriterTest extends \PHPUnit\Framework\TestCase
{
    private $message;

    private $filePutContent;

    private $conf;

    public function setUp(): void
    {
        $this->conf = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->message = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Message::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filePutContent = $this
            ->getMockBuilder(\Sensorario\Yaphlo\FilePutContentWrapper::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @test */
    public function writeIntoFile()
    {
        $this->filePutContent
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
            ->expects($this->once())
            ->method('enabledChannels')
            ->willReturn(['all']);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->filePutContent,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function doNotWriteIntoFile()
    {
        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_FATAL);

        $this->filePutContent
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
            $this->filePutContent,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function writerNeverAppendWheneverEnabledChannelsIsEmpty()
    {
        $this->filePutContent
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
            ->expects($this->once())
            ->method('enabledChannels')
            ->willReturn([]);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->filePutContent,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function neverAppendIfChannelEnabledIsNotOfMessage()
    {
        $this->filePutContent
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
            ->expects($this->once())
            ->method('enabledChannels')
            ->willReturn(['foo']);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->filePutContent,
        );

        $writer->write($this->message);
    }

    /** @test */
    public function appendIfChannelEnabledIsSmaeOfMessage()
    {
        $this->filePutContent
            ->expects($this->once())
            ->method('append');

        $this->message
            ->expects($this->once())
            ->method('render');

        $this->message
            ->expects($this->once())
            ->method('getChannel')
            ->willReturn('foo');

        $this->conf
            ->expects($this->once())
            ->method('level')
            ->willReturn(Message::LEVEL_INFO);

        $this->conf
            ->expects($this->once())
            ->method('enabledChannels')
            ->willReturn(['foo']);

        $this->message
            ->expects($this->once())
            ->method('isPrintableWithLevel')
            ->willReturn(true);

        $writer = new Writer(
            $this->conf,
            $this->filePutContent,
        );

        $writer->write($this->message);
    }
}
