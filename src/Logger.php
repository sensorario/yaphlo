<?php

namespace Sensorario\Yaphlo;

class Logger
{
    public function __construct(
        private Message $msg,
        private Writer $writer,
    ) {}

    private function write(array $message, string $level)
    {
        $this->msg->setContent($message);
        $this->msg->setLevel($level);
        $this->writer->write($this->msg);
    }

    public function info(array $message): void
    {
        $this->write($message, Message::LEVEL_INFO);
    }

    public function error(array $message): void
    {
        $this->write($message, Message::LEVEL_ERROR);
    }
}
