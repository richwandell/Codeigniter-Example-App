<?php

/**
 * A custom exception used for the form validation logic
 * Class MissingParametersException
 */
class MissingParametersException extends Exception
{

    private $missing = array();

    public function __construct($missing = array(), $message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        log_message('error', "[MissingParameters] $message");
        $this->missing = $missing;
    }

    public function getMissing()
    {
        return $this->missing;
    }
}