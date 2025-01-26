<?php

namespace App\Exception;

use Throwable;

class BadRequestException extends \Exception
{

    private $messages;
    
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setMessages(array $messages) 
    {
        $this->messages = $messages;
    }

    public function getMessages() 
    {
        return $this->messages;
    }
}