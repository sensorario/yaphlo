<?php

namespace Sensorario\Yaphlo;

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

        if ($this->checker->mustChannelBeHidden($message)) {
            return;
        }

        $this->writerAdapter->append($message->render());
    }
}
