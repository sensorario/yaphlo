<?php

namespace Sensorario\Yaphlo\Exceptions;

class MissingLevelException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Oops! Missing level  Exception!!';
    }
}
