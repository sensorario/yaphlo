<?php

namespace Sensorario\Yaphlo;

class Message
{
    const LEVEL_INFO = 'INFO';

    const LEVEL_ERROR = 'ERROR';

    const LEVEL_WARNING = 'WARNING';

    const LEVEL_FATAL = 'FATAL';

    private ?string $channel = null;

    private ?string $level = null;

    private array $content = [];

    private ?\DateTime $datetime;

    private static array $levelMap = [
        self::LEVEL_INFO,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
        self::LEVEL_FATAL,
    ];

    public function __construct()
    {
        $this->datetime = new \DateTime();
    }

    public static function levelMap(): array
    {
        return self::$levelMap;
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
        if (!in_array($level, self::$levelMap)) {
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
            $items = [];

            $items[] = $datetime;

            if ($this->level !== null) {
                $items[] = '[' . $this->level . ']';
            }

            if (
                $this->channel !== null
                && $this->channel !== ''
            ) {
                $items[] = '[' . $this->channel . ']';
            }

            $items[] = $line;

            $rendered[] = join(' ', $items);
        }

        return implode("\n", $rendered);
    }

    public function forceDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function inverseMap(): array
    {
        return array_flip(
            $this->levelMap()
        );
    }

    public function isPrintableWithLevel(string $level): bool
    {
        $map = $this->inverseMap();
        $levelNumber = $map[$level];
        $levelMe = $map[$this->level] ?? 0;

        return $levelNumber >= $levelMe;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }
}
