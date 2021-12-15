<?php

namespace Sensorario\Yaphlo;

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
