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
            $shows = [];
            $shows[] = $channels !== [] && in_array($message->getChannel(), $channels);
            $shows[] = $channels === ['all'];

            foreach ($shows as $show) {
                if ($show === true) {
                    $this->filePutContent->append($message->render());
                }
            }
        }
    }
}
