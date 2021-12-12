<?php

namespace Sensorario\Yaphlo;

class Writer
{
    public function __construct(
        private Config $conf,
        private FilePutContentWrapper $filePutContent,
    ) {}

    public function write(Message $message): void
    {
        $confLevel = $this->conf->level();
        if ($message->isPrintableWithLevel($confLevel)) {
            $channels = $this->conf->enabledChannels();
            if (
                $channels === ['all']
                || ($channels !== [] && in_array($message->getChannel(), $channels))
            ) {
                $this->filePutContent->append($message->render());
            }
        }
    }
}
