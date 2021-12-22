<?php

namespace Sensorario\Yaphlo;

interface LoggerWriter
{
    /** @param array<int, string> $message */
    public function write(
        array $message,
        string $level,
        string $channel,
    ): void;

    public function addListener(Listeners\Listener $listener): void;
}
