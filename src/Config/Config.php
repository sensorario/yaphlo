<?php

namespace Sensorario\Yaphlo\Config;

interface Config
{
    public function level(): string;

    /** @return array<int, string> */
    public function enabledChannels(): array;
}
