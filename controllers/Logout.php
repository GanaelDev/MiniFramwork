<?php

require_once 'Controller.php';  // Inclure la classe de base
require_once("middlewares/Validator.php");

class LogoutController extends Controller
{


    public function __construct($language)
    {
        parent::__construct($language);
    }
    

    // Méthode principale pour gérer les requêtes
    public function handleRequest()
    {   
        
        if (session_status() != 2)
        {
            session_start();
        }
        if (isset($_SESSION['Token']))
        {
            $token = $_SESSION['Token'];
            $dao = new TokenDao();
            $dao->DeleteAllUser($token->getIdUser());
            session_destroy();
        }
       
        unset($_SESSION['User']);
        unset($_SESSION['Token']);

        $this->redirectToUri('login');
    }
}
