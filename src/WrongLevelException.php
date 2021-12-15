<?php

namespace Sensorario\Yaphlo;

class WrongLevelException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Oops! Wrong level Exception!!';
    }
}
