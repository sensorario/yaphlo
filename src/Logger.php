<?php

namespace Sensorario\Yaphlo;

class Logger implements LoggerWriter
{
    public function __construct(
        private Message $msg,
        private Writer $writer,
    ) {}

    public function write(
        array $message,
        string $level,
        string $channel = '',
    ): void {
        $this->msg->setContent($message);
        $this->msg->setLevel($level);
        $this->msg->setChannel($channel);
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
}
