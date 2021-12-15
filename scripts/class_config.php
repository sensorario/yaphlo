<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\WriterAdapter;
use Sensorario\Yaphlo\Config;
use Sensorario\Yaphlo\ArrayConfig;
use Sensorario\Yaphlo\CustomConfig;

$logger = new Logger(
    new Message,
    new Writer(
        new CustomConfig(
            Message::LEVEL_INFO,
            ['channel A'],
        ),
        new WriterAdapter(
            __DIR__ . '/logger.log',
        )
    )
);

$logger->info(['write' => 'this']);
$logger->error(['write' => 'this']);

$logger->info(['write' => 'this'], 'channel A');
$logger->info(['write' => 'this'], 'channel B');

