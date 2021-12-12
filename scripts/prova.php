<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\WriterAdapter;
use Sensorario\Yaphlo\Config;

// new CustomConfig(
//     Message::LEVEL_INFO,
//     ['channel A'],
// ),

class CustomConfig implements Config
{
    public function __construct(
        private string $level,
        private array $channels,
    ) {}

    public function level()
    {
        return $this->level;
    }

    public function enabledChannels()
    {
        return $this->channels;
    }
}

$config = [
    'logger' => [
        'level' => 'INFO',
        'enabledChannels' => [
            'channel A',
        ],
    ],
];

class ArrayConfig implements Config
{
    public function __construct(private array $config) { }

    public function level()
    {
        return $this->config['logger']['level'];
    }

    public function enabledChannels()
    {
        return $this->config['logger']['enabledChannels'];
    }
}

$logger = new Logger(
    new Message,
    new Writer(
        new ArrayConfig($config),
        new WriterAdapter(
            __DIR__ . '/logger.log',
        )
    )
);

$logger->info(['write' => 'this']);
$logger->error(['write' => 'this']);
$logger->fatal(['write' => 'this']);
$logger->warning(['write' => 'this']);

$logger->info(['write' => 'this'], 'channel A');
$logger->info(['write' => 'this'], 'channel B');

