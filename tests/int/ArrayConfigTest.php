<?php

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\ChannelVisibilityChecker;
use Sensorario\Yaphlo\WriterAdapter;
use Sensorario\Yaphlo\Listeners\Listener;
use Sensorario\Yaphlo\Config\ArrayConfig;

class ArrayConfigTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function logSomethig()
    {
        $config = [
            'logger' => [
                'level' => 'INFO',
                'enabledChannels' => [
                    'channel A',
                ],
            ],
        ];

        $logger = new Logger(
            new Message,
            new Writer(
                new ArrayConfig($config),
                new WriterAdapter(
                    __DIR__ . '/logger.log',
                ),
                new ChannelVisibilityChecker(new ArrayConfig($config)),
            )
        );

        $this->listener = $this->getMockBuilder(Listener::class)
            ->getMock();

        $this->listener->expects($this->once())
            ->method('read')
            ->with(json_encode(['write' => 'this']));

        $logger->addListener($this->listener);

        $logger->info(['write' => 'this']);
    }

    /** @test */
    public function logNothing()
    {
        $config = [
            'logger' => [
                'level' => 'INFO',
                'enabledChannels' => [],
            ],
        ];

        $logger = new Logger(
            new Message,
            new Writer(
                new ArrayConfig($config),
                new WriterAdapter(
                    __DIR__ . '/logger.log',
                ),
                new ChannelVisibilityChecker(new ArrayConfig($config)),
            )
        );

        $this->listener = $this->getMockBuilder(Listener::class)
            ->getMock();

        $this->listener->expects($this->once())
            ->method('read')
            ->with(json_encode(['write' => 'this']));

        $logger->addListener($this->listener);

        $logger->info(['write' => 'this'], 'foo');
    }
}

