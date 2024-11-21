<?php

abstract class Middleware {

    protected $errorMessage;
    protected $errorCode;
    protected $language;


    public function __construct($language = 'en', $errorMessage = 'Unregister Error', $errorCode = 500) 
    {

        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->language = $language;

    }

    abstract public function run($params, $requiredParams);
    abstract protected function raiseError();

}
