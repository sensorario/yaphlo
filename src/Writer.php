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
            $this->filePutContent->append($message->render());
        }
    }
}
