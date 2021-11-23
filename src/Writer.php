<?php

namespace Sensorario\Yaphlo;

class Writer
{
    public function __construct(
        private FilePutContentWrapper $filePutContent,
    ) {}

    public function write(Message $message): void
    {
        $this->filePutContent->append($message->render());
    }
}
