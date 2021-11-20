<?php

namespace Sensorario\Yaphlo\Tests;

use Sensorario\Yaphlo\Writer;

class WriterTest extends \PHPUnit\Framework\TestCase
{
    private $message;

    private $filePutContent;

    public function setUp(): void
    {
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

        $writer = new Writer($this->filePutContent);
        $writer->write($this->message);
    }
}
