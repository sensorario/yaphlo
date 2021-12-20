<?php

namespace Sensorario\Yaphlo;

class LoggerTest extends \PHPUnit\Framework\TestCase
{
    private $message;

    private $writer;
    
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

    /**
     * @test
     * @dataProvider levels
     */
    public function istantiate($level)
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
            ->with($level);

        $this->writer->expects($this->once())
            ->method('write')
            ->with($this->message);

        $logger->{strtolower($level)}($message);
    }

    public function levels()
    {
        $levels = [];
        foreach(Message::levelMap() as $level) {
            $levels[] = [$level];
        }

        return $levels;
    }
}
