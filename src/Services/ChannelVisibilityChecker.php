<?php

namespace Sensorario\Yaphlo\Services;

use Sensorario\Yaphlo\Config;
use Sensorario\Yaphlo\Message;

class ChannelVisibilityChecker
{
    public function __construct(
        private Config\Config $conf,
    ) {}

    public function mustChannelBeHidden(Message $message): bool
    {
        $channels = $this->conf->enabledChannels();

        $shows = [];
        $shows[] = $channels !== [] && in_array($message->getChannel(), $channels);
        $shows[] = $channels === ['all'];

        $keepMessageHidden = true;
        foreach ($shows as $channelMustBeVisible) {
            if ($channelMustBeVisible === true) {
                $keepMessageHidden = false;
            }
        }

        return $keepMessageHidden;
    }
}
