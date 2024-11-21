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
            case 'account':
                $res = AccountController::class;
                break;
            case 'home':
                $res = HomeController::class;
                break;
            case 'login':
                $res = LoginController::class;
                break;
            case 'user':
                $res = UserController::class;
                break;
            case 'logout':
                $res = LogoutController::class;
                break;
            case 'admin':
                $res = AdminController::class;
                break;
            case 'customer':
                $res = CustomerController::class;
                break;
            case 'forfait':
                $res = ForfaitController::class;
                break;
            default: {
                throw new Error404NotFound('en');
                break;
            }
        }

        return $res;
    }






}

?>