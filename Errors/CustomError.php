<?php
abstract class CustomError extends Exception 
{
    public function __construct(string $message = "", int $code = 0) 
    {
        parent::__construct($message, $code, );
    }

    abstract public function getType();
    
}