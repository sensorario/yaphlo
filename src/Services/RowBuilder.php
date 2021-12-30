<?php

namespace Sensorario\Yaphlo\Services;

class RowBuilder
{
    private $items;

    private $datetime;

    private $level;

    private $channel;

    private $line;

    public function reset(): void
    {
        $this->items = [];
    }

    public function addDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime->format('Y-m-d H:i:s');
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
        $this->items[] = "$line";
    }

    public function rendered(int $index): string
    {
        return join(' ', $this->items);
    }
}
