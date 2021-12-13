# yaphlo

Yet another php logger

## Example

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Yaphlo\Logger;
use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Writer;
use Sensorario\Yaphlo\WriterAdapter;

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
        )
    )
);

$logger->info(['write' => 'this']);
$logger->error(['write' => 'this'], 'channel A');
```

## Log

    [2021-12-13 00:21:08] [INFO] [channel A] {
    [2021-12-13 00:21:08] [INFO] [channel A]     "write": "this"
    [2021-12-13 00:21:08] [INFO] [channel A] }
