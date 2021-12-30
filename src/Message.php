<?php

namespace Sensorario\Yaphlo;

use Sensorario\Yaphlo\Services\RowBuilder;

class Message
{
    const LEVEL_INFO = 'INFO';

    const LEVEL_ERROR = 'ERROR';

    const LEVEL_WARNING = 'WARNING';

    const LEVEL_FATAL = 'FATAL';

    private ?string $channel = null;

    private ?string $level = null;

    /** @var array<int, string> $content */
    private array $content = [];

    private ?\DateTime $datetime;

    /** @var array<int, string> $levelMap */
    private static array $levelMap = [
        self::LEVEL_INFO,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
        self::LEVEL_FATAL,
    ];

    public function __construct(
        private RowBuilder $builder,
    ) {
        $this->datetime = new \DateTime();
    }

    /** @return array<int, string> */
    public static function levelMap(): array
    {
        return self::$levelMap;
    }

    /** @param array<int, string> $content */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    /** @return array<int, string> $content */
    public function content(): array
    {
        return $this->content;
    }

    public function setLevel(string $level): void
    {
        if (!in_array($level, self::$levelMap)) {
            throw new Exceptions\WrongLevelException();
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

        $this->builder->reset();
        $this->builder->addLevel($this->level);
        $this->builder->addChannel($this->channel);
        $this->builder->addDateTime($this->datetime);

        foreach (explode("\n", $encoded) as $index => $line) {
            $this->builder->addLine($line);

            $rendered[] = $this->builder->rendered($index);
        }

        return implode("\n", $rendered);
    }

    public function forceDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    /** @return array<string, int> $content */
    public function inverseMap(): array
    {
        return array_flip(
            $this->levelMap()
        );
    }

    public function isPrintableWithLevel(string $level): bool
    {
        if ($this->level === null) {
            throw new Exceptions\MissingLevelException();
        }

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
