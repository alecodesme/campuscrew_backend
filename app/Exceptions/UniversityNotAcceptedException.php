<?php

namespace App\Exceptions;

use Exception;

class UniversityNotAcceptedException extends Exception
{
    public function __construct($message = "University status is not accepted for login.", $code = 403)
    {
        parent::__construct($message, $code);
    }
}
