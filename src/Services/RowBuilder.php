<?php

namespace Sensorario\Yaphlo\Services;

use DateTime;
use Sensorario\Yaphlo\Message;

class RowBuilder
{
    private string $datetime;

    private string $level;

    private string $channel;

    private string $line;

    public function __construct()
    {
        $this->datetime = (new \DateTime('now'))->format('[Y-m-d H:i:s]');
    }

    public function reset(): void
    {
        $this->datetime = (new \DateTime('now'))->format('[Y-m-d H:i:s]');
        $this->level = Message::LEVEL_INFO;
        $this->channel = '';
        $this->line = '';
    }

    public function addDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime->format('[Y-m-d H:i:s]');
    }

    public function addLevel(?string $level): void
    {
        $this->level = $level;
    }

    public function addChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    public function addLine(string $line): void
    {
        $this->line = $line;
    }

    public function rendered(): string
    {
        $row = [];
        $row[] = $this->datetime;

        if (!isset($this->line)) {
            throw new \RuntimeException('Oops! Missing line!');
        }

        if (!isset($this->level)) {
            throw new \RuntimeException('Oops! Missing level!');
        }

        if (!isset($this->channel)) {
            throw new \RuntimeException('Oops! Missing channel!');
        }

        if ($this->level !== null) {
            $row[] = '[' . $this->level . ']';
        }
        if ($this->channel !== '') {
            $row[] = '[' . $this->channel . ']';
        }
        $row[] = $this->line;
        $rendered = join(' ', $row);
        return trim($rendered);
    }
}
