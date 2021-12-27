<?php

namespace Sensorario\Yaphlo\Services\Base;

use Sensorario\Yaphlo\Message;
use Sensorario\Yaphlo\Config\Config;

interface Checker
{
    public function mustChannelBeHidden(Message $message): bool;

    public function setConfig(Config $config): void;
}
