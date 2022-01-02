<?php

namespace Sensorario\Yaphlo\Writers;

/** @codeCoverageIgnore */
class WriterAdapter implements FileWriterWrapper
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
