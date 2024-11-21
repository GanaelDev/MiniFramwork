<?php
require_once('CustomError.php');
class ErrorcheckParameters extends CustomError 
{
    public function __construct($language, $message = null, $code = 500) 
    {
        if(!isset($message))
        {
            require_once("lang/".$language.".php");
            $message = $message ? $message : getTraduction('errors.request') . ' ' . getTraduction('errors.checkParameters.required');
        }
        parent::__construct($message, $code);
    }

    public function getType() 
    {
        return ErrorcheckParameters::class;
    }
}