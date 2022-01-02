<?php

namespace Sensorario\Yaphlo\Exceptions;

class WrongLevelException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Oops! Wrong level Exception!!';
    }
}
