# yaphlo

Yet another php logger

## Example with class configuration

```php
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
```


## Example with array configuration

I prefer this way because configuration can be placed into a configuration file and bla bla bla.

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

## What both scripts will write in logs, ...

    [2021-12-13 00:21:08] [INFO] [channel A] {
    [2021-12-13 00:21:08] [INFO] [channel A]     "write": "this"
    [2021-12-13 00:21:08] [INFO] [channel A] }
