<?php

namespace Sensorario\Yaphlo;

class CustomConfig implements Config
{
    /** @param array<int, string> $channels */
    public function __construct(
        private string $level,
        private array $channels,
    ) {}

    public function level(): string
    {
        return $this->level;
    }

    public function enabledChannels(): array
    {
        return $this->channels;
    }
}
