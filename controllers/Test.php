<?php

require_once 'Controller.php';  // Inclure la classe de base

class TestController extends Controller {


    public function __construct($language)
    {
        parent::__construct($language);
        // Initialisation, par exemple, connexion à la base de données
    }


    // Méthode principale pour gérer les requêtes
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $params = $_GET; // Utiliser $_POST ou $_PUT si nécessaire
        try {

            switch ($method) {
                case 'POST':
                    $data = $_POST;  // Données envoyées avec la requête POST
                    $this->create($data);
                    break;
                case 'GET':
                    $this->handlerRead($params);
                    break;
                case 'PUT':
                    $middlewares = $this->getMiddlewareForPutOrDelete();
                    parse_str(file_get_contents("php://input"), $data);
                    $data = json_decode(key($data), true);  // Données pour la requête PUT
                    $params = $this->runMiddlewares($middlewares, $params, $this->getMiddlewareParamsForPut());
                    $this->update($params, $data);
                    break;
                case 'DELETE':
                    $middlewares = $this->getMiddlewareForPutOrDelete();
                    $params = $this->runMiddlewares($middlewares, $params, $this->getMiddlewareParamsForDelete());
                    $this->delete($params);
                    break;
                default:
                    $this->sendResponseJson(['error' => 'Method Not Allowed'], 405);
            }
        }
        catch (CustomError $e) 
        {
            switch($e->getType()) 
            {
                case 'checkParameters':
                    {
                        // Code pour 'checkParameters'
                        break;
                    }
                    
            
                default:
                    {
                       // Code par défaut
                       break; 
                    }
                        
            }
        }
    }



    // CRUD Operations
    protected function create($data) {

        // Logique pour créer un utilisateur
        $this->sendResponseJson(['message' => 'User created!']);
    }

    protected function readOne($id) 
    {
        $this->sendResponseJson(['message' => 'User created!']);
    }
    protected function readAll() 
    {
        $this->sendResponseJson(['message' => 'User created!']);
   
    }

    protected function handlerRead($params)
    {
        if(isset($params['id']))
        {
            $this->readOne($params['id']);
        }
        else
        {
            $this->readAll();
        }    
    }

    protected function update($params, $data) 
    {

        $this->sendResponseJson(['message' => 'User created!']);
    }

    protected function delete($params) 
    {
        $this->sendResponseJson(['message' => 'User created!']);
    }

    private function getMiddlewareForPutOrDelete() : array
    {
        MiddlewareLoader::load_one('checkParameters');
       
        return  [checkParameters::class] ;
    }


    private function getMiddlewareParamsForPut() : array
    {
        return [checkParameters::class =>  ['id']];
    }

    private function getMiddlewareParamsForDelete() : array
    {
        return [checkParameters::class =>  ['id']];
    }

}

