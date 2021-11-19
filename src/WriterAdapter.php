<?php

namespace Sensorario\Yaphlo;

/** @codeCoverageIgnore */
class WriterAdapter implements FilePutContentWrapper
{
    public function __construct(
        private string $filePath,
    ) { }

    public function append(string $message): bool
    {
        return file_put_contents(
            $this->filePath,
            $message . "\n",
            FILE_APPEND
        );
    }
}
