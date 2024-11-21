<?php
require_once("Middleware.php");
require_once("./Errors/ErrorcheckParameters.php");

class checkParameters extends Middleware 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function run($params , $requiredParams ): array {
        $raiseError = false;
        foreach ($requiredParams as $param) {
            if (!isset($params[$param]) || empty($params[$param]))
            {
                if(!$raiseError)
                {
                    $this->errorMessage = "Bad Request: '$param' parameter is required.";
                    $raiseError = true;
                }
                else
                {
                    $this->errorMessage .= "\nBad Request: '$param' parameter is required.";
                }
                
            }
        }
        if($raiseError)
        {
            $this->raiseError();
        }
        // return un array merge des données d'avant et des trucs en plus si nécessaire. Si doublon il faudra juste faire attention
        return $params;
    }
    public function raiseError()
    {
        throw new ErrorcheckParameters($this->language,$this->errorMessage);
    }
}