<?php
require_once('./Errors/CustomError.php');
class Error404NotFound extends CustomError 
{
    public function __construct($language, $message = null, $code = 500) 
    {
        if(!isset($message))
        {
            require_once("lang/".$language.".php");
            $message = getTraduction('errors.request') . ' ' . getTraduction('errors.404.label');
        }
        parent::__construct($message, $code);
    }

    public function getType() 
    {
        return Error404NotFound::class;
    }
}