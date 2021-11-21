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

$logger = new Logger(
    new Message,
    new Writer(
        new WriterAdapter(
            __DIR__ . '/logger.log',
        )
    )
);

$logger->info(['write' => 'this']);
$logger->error(['write' => 'this']);
```

## Log

    [2021-11-19 22:54:54] [INFO] {
    [2021-11-19 22:54:54] [INFO]     "write": "this"
    [2021-11-19 22:54:54] [INFO] }
    [2021-11-19 22:54:54] [ERROR] {
    [2021-11-19 22:54:54] [ERROR]     "write": "this"
    [2021-11-19 22:54:54] [ERROR] }
