<?php

namespace Sensorario\Yaphlo;

use Sensorario\Yaphlo\Objects\Message;

class Logger implements LoggerWriter
{
    private bool $listenerEnabled = false;

    private Listeners\Listener $listener;

    public function __construct(
        private Message $msg,
        private Writers\Writer $writer,
    ) {}

    public function write(
        array $message,
        string $level,
        string $channel = '',
    ): void {
        $this->msg->setContent($message);
        $this->msg->setLevel($level);
        $this->msg->setChannel($channel);
        if ($this->listenerEnabled === true) {
            $this->listener->read(json_encode($message));
        }
        $this->writer->write($this->msg);
    }

    /** @param array<int, string> $message */
    public function info(
        array $message,
        string $channel = '',
    ): void {
        $this->write(
            $message,
            Message::LEVEL_INFO,
            $channel,
        );
    }

    /** @param array<int, string> $message */
    public function error(
        array $message,
        string $channel = '',
    ): void {
        $this->write(
            $message,
            Message::LEVEL_ERROR,
            $channel,
        );
    }

    /** @param array<int, string> $message */
    public function fatal(
        array $message,
        string $channel = '',
    ): void {
        $this->write(
            $message,
            Message::LEVEL_FATAL,
            $channel,
        );
    }

    /** @param array<int, string> $message */
    public function warning(
        array $message,
        string $channel = '',
    ): void {
        $this->write(
            $message,
            Message::LEVEL_WARNING,
            $channel
        );
    }

    public function addListener(Listeners\Listener $listener): void
    {
        $this->listener = $listener;
        $this->listenerEnabled = true;
    }
}
