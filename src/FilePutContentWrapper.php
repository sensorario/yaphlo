<?php

namespace Sensorario\Yaphlo;

interface FilePutContentWrapper
{
    public function __construct(
        string $filePath
    );

    public function append(
        string $message
    ): bool;
}
