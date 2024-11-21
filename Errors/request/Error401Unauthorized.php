<?php
require_once('./Errors/CustomError.php');
class Error401Unauthorized  extends CustomError 
{
    public function __construct($language, $message = null, $code = 401) 
    {
        if(!isset($message))
        {
            require_once("lang/".$language.".php");
            $message = getTraduction('errors.request') . ' ' . getTraduction('errors.401.label');
        }
        parent::__construct($message, $code);
    }

    public function getType() 
    {
        return Error401Unauthorized::class;
    }
}