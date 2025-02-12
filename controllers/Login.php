<?php

require_once 'Controller.php';  // Inclure la classe de base
require_once("middlewares/Validator.php");

class LoginController extends Controller
{


    public function __construct($language)
    {
        parent::__construct($language);
    }


    // Méthode principale pour gérer les requêtes
    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $params = $_GET; // Utiliser $_POST ou $_PUT si nécessaire
        try {
            switch ($method) {
                case 'POST':
                    $middlewares = $this->getMiddlewareForPost();
                    $data = $_POST;  // Données envoyées avec la requête POST
                    $data = $this->runMiddlewares( $middlewares, $data, $this->getMiddlewareParamsForPost());
                    $this->checkLoginData($data);
                    break;
                case 'GET':
                    $this->handlerRead($params);
                    break;
                default:
                    $this->sendResponseJson(['error' => 'Method Not Allowed'], 405);
            }
        } catch (CustomError $e) {

            switch ($e->getType()) {
                case ErrorcheckParameters::class: {
                    require_once("lang/".$this->current_language.".php");
                    throw new ErrorcheckParameters($this->current_language, getTraduction('errors.checkParameters.login'));
                    break; 
                }
                default: {
                    // Code par défaut
                    break;
                }
            }
        }
    }

    // CRUD Operations
    protected function checkLoginData($data)
    {
        $validator = new Validator();
        $res = $validator->ValidateUser($data['email'], $data['password']);
        if(!$res)
        {
            throw new ErrorcheckParameters($this->current_language);
        }
        //$this->sendResponseView('Home', ['language' => 'en'], 'en', 'login.css', false, false);
        $this->redirectToUri('home');
    }
    protected function handlerRead($params)
    {
       // $this->sendResponseView('Login', ['language' => 'en'], 'en', 'login.css', false, false);
       echo('TOTO');
    }


    private function getMiddlewareForPost() : array
    {
        MiddlewareLoader::load_one('checkParameters');
       
        return  [checkParameters::class] ;
    }

    private function getMiddlewareParamsForPost() : array
    {
        return [checkParameters::class =>  ['email', 'password']];
    }
}