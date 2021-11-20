<?php

namespace Sensorario\Yaphlo;

class Message
{
    const LEVEL_INFO = 'INFO';

    const LEVEL_ERROR = 'ERROR';

    private ?string $level = null;

    private array $content = [];

    private ?\DateTime $datetime;

    private $levelMap = [
        self::LEVEL_INFO,
        self::LEVEL_ERROR,
    ];

    public function __construct()
    {
        $this->datetime = new \DateTime();
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    public function content(): array
    {
        return $this->content;
    }

    public function setLevel(string $level): void
    {
        if (!in_array($level, $this->levelMap)) {
            throw new WrongLevelException();
        }

        $this->level = $level;
    }

    public function render(): string
    {
        $encoded = json_encode(
            $this->content,
            JSON_PRETTY_PRINT,
        );

        $rendered = [];

        $datetime = $this->datetime->format('[Y-m-d H:i:s]');
        foreach (explode("\n", $encoded) as $line) {
            if ($this->level === null) {
                $rendered[] = $datetime . ' ' . $line;
            } else {
                $rendered[] = $datetime . ' ' . '[' . $this->level . ']' . ' ' . $line;
            }
        }

        return implode("\n", $rendered);
    }

    public function forceDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }
}
