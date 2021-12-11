<?php

namespace Sensorario\Yaphlo\Tests;

use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\Message;

class WriterTest extends \PHPUnit\Framework\TestCase
{
    private $message;

    private $filePutContent;

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
}
