<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Yaphlo\FilePutContentWrapper;
use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\WriterAdapter;

// definizione per il container o service locator
$logger = new Logger(
    new Message,
    new Writer(
        new WriterAdapter(
            __DIR__ . '/logger.log',
        )
    )
);

// logga cose
$logger->info(['scrivi' => 'questo']);
$logger->error(['scrivi' => 'questo']);
