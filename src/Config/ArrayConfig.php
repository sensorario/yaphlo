<?php

namespace Sensorario\Yaphlo\Config;

class ArrayConfig implements Config
{
    /** @param array<string, array{level:string, enabledChannels: array<int, string>}> $config */
    public function __construct(private array $config) { }

    public function level(): string
    {
        return $this->config['logger']['level'];
    }

    /** @return array<int, string> */
    public function enabledChannels(): array
    {
        return $this->config['logger']['enabledChannels'];
    }
}
