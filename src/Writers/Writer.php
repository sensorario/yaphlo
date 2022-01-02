<?php

namespace Sensorario\Yaphlo\Writers;

use Sensorario\Yaphlo\Objects\Message;
use Sensorario\Yaphlo\Config;
use Sensorario\Yaphlo\Services\ChannelVisibilityChecker;

class Writer
{
    public function __construct(
        private Config\Config $conf,
        private FileWriterWrapper $writerAdapter,
        private ChannelVisibilityChecker $checker,
    ) {}

    public function write(Message $message): void
    {
        $confLevel = $this->conf->level();

        if (!$message->isPrintableWithLevel($confLevel)) {
            return;
        }

        $this->checker->setConfig($this->conf);
        if ($this->checker->mustChannelBeHidden($message)) {
            return;
        }

        $this->writerAdapter->append($message->render());
    }
}
