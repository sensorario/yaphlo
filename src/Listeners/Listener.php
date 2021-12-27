<?php

namespace Sensorario\Yaphlo\Listeners;

interface Listener
{
    public function read(string $message): void;
}
