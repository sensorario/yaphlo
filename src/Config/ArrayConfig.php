<?php

namespace Sensorario\Yaphlo\Config;

class ArrayConfig implements Config
{
    public function __construct(private array $config) { }

    public function level()
    {
        return $this->config['logger']['level'];
    }

    public function enabledChannels()
    {
        return $this->config['logger']['enabledChannels'];
    }
}
