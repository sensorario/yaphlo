<?php

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\ChannelVisibilityChecker;
use Sensorario\Yaphlo\Listeners\Listener;
use Sensorario\Yaphlo\Writers\Writer;
use Sensorario\Yaphlo\Writers\WriterAdapter;
use Sensorario\Yaphlo\Config\Config;
use Sensorario\Yaphlo\Config\CustomConfig;

class ClassConfigTest extends PHPUnit\Framework\TestCase
{
    private $listener;

    /** @test */
    public function logSomethig()
    {
        $config = new CustomConfig(
            Message::LEVEL_INFO,
            ['channel A'],
        );

        $logger = new Logger(
            new Message,
            new Writer(
                $config,
                new WriterAdapter(
                    __DIR__ . '/logger.log',
                ),
                new ChannelVisibilityChecker($config),
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
}

