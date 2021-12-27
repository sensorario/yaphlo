<?php

namespace Sensorario\Yaphlo;

interface FileWriterWrapper
{
    public function __construct(
        string $filePath
    );

    public function append(
        string $message
    ): bool;
}
