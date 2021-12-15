<?php

namespace Sensorario\Yaphlo;

interface LoggerWriter
{
    public function write(
        array $message,
        string $level,
        string $channel,
    ): void;
}
