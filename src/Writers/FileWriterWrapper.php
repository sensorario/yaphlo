<?php

namespace Sensorario\Yaphlo\Writers;

interface FileWriterWrapper
{
    public function __construct(
        string $filePath
    );

    public function append(
        string $message
    ): bool;
}
