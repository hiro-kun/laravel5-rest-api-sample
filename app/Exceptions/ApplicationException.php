<?php

namespace App\Exceptions;

class ApplicationException extends \Exception
{
    private $errorField;
    private $httpStatus;
    private $request;

    public function __construct($message, $code = 0, $field = null, $httpStatus = null, Exception $previous = null)
    {
        $this->errorField = $field;
        $this->httpStatus = $httpStatus;

        parent::__construct($message, $code, $previous);
    }

    public function getErrorField()
    {
        return $this->errorField;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}
