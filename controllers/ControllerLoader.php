<?php
require_once("./middlewares/Validator.php");
require_once("Errors/request/Error401Unauthorized.php");
require_once("Errors/request/Error404NotFound.php");


class ControllerLoader
{
    protected static $expludedFile = [
        'Controller',
        'ControllerLoader'
    ];

    protected static $UnprotectedController = [
        'Login',
        'Account',
        'Logout',

        '__hide__Install_database',
        '__hide__InsertdDatas',
    ];


    // depuis une string 
    // chargé le controlleur
    public static function load_one(string $nameController)
    {
        $controllerFile = __DIR__ . '/' . ucfirst($nameController) . '.php';

        if (file_exists($controllerFile) && !in_array(ucfirst($nameController), self::$expludedFile)) {

            if (in_array(ucfirst($nameController), self::$UnprotectedController)) {
                require_once $controllerFile; // Inclure le fichier contrôleur
            } else {
                $validator = new Validator();
                if ($validator->ValidateToken()) {
                    require_once $controllerFile; // Inclure le fichier contrôleur
                } else {
                    throw new Error401Unauthorized('en');
                }
            }
        } else {
            throw new Error404NotFound('en');   
            //&& !in_array(ucfirst($nameController), self::$expludedFile)
            //$this->notFound(); // Afficher une erreur 404 si le fichier n'existe pas
        }
    }

    // depuis la meme string 
    // renvoyé la class
    public static function getClassForRoute(string $nameController)
    {
        $res = null;
        switch ($nameController) {
            case 'test':
                $res = TestController::class;
            default: {
                throw new Error404NotFound('en');
                break;
            }
        }

        return $res;
    }






}

?>