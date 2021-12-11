<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\WriterAdapter;
use Sensorario\Yaphlo\Config;

class CustomConfig implements Config
{
    public function __construct(
        private string $level
    ) {}

    public function level()
    {
        return $this->level;
    }
}

$logger = new Logger(
    new Message,
    new Writer(
        new CustomConfig(Message::LEVEL_INFO),
        new WriterAdapter(
            __DIR__ . '/logger.log',
        )
    )
);

$logger->info(['write' => 'this']);
$logger->error(['write' => 'this']);
$logger->fatal(['write' => 'this']);
$logger->warning(['write' => 'this']);

$logger->info(['write' => 'this'], 'channel');
