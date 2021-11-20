<?php

namespace Sensorario\Yaphlo;

class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $this->message = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Message::class)
            ->getMock();

        $this->writer = $this
            ->getMockBuilder(\Sensorario\Yaphlo\Writer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @test */
    public function istantiate()
    {
        $logger = new Logger(
            $this->message,
            $this->writer
        );

        $message = ['answer' => 42];

        $this->message->expects($this->once())
            ->method('setContent')
            ->with($message);
        $this->message->expects($this->once())
            ->method('setLevel')
            ->with(\Sensorario\Yaphlo\Message::LEVEL_INFO);

        $this->writer->expects($this->once())
            ->method('write')
            ->with($this->message);

        $logger->info($message);
    }

    /** @test */
    public function logErrorMessages()
    {
        $logger = new Logger(
            $this->message,
            $this->writer
        );

        $message = ['answer' => 42];

        $this->message->expects($this->once())
            ->method('setContent')
            ->with($message);
        $this->message->expects($this->once())
            ->method('setLevel')
            ->with(\Sensorario\Yaphlo\Message::LEVEL_ERROR);

        $this->writer->expects($this->once())
            ->method('write')
            ->with($this->message);

        $logger->error($message);
    }
}
