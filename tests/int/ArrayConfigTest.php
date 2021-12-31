<?php

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Services\ChannelVisibilityChecker;
use Sensorario\Yaphlo\Services\RowBuilder\RowBuilder;
use Sensorario\Yaphlo\Writers\Writer;
use Sensorario\Yaphlo\Writers\WriterAdapter;
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
            new Message(
                new RowBuilder()
            ),
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
            new Message(
                new RowBuilder()
            ),
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

