<?php

require_once("views/View.php");
require_once("middlewares/Validator.php");
require_once("middlewares/MiddlewareLoader.php");



/**
 * Classe abstraite Controller
 * 
 * Cette classe sert de base pour tous les contrôleurs de l'application. Elle fournit des méthodes pour envoyer
 * des réponses en JSON ou en vue, gérer les middlewares et traiter les erreurs. Chaque contrôleur concret doit 
 * implémenter la méthode abstraite `handleRequest`.
 */
abstract class Controller
{
    protected string $current_language;
    public function __construct($language)
    {
        $this->current_language = $language;

    }

    /**
     * Méthode pour envoyer une réponse au format JSON.
     * 
     * @param mixed $data Les données à envoyer sous forme de réponse JSON.
     * @param int $status Le code de statut HTTP de la réponse (par défaut : 200).
     * 
     * Cette méthode définit l'en-tête HTTP en tant que 'application/json', puis elle encode et envoie les données au format JSON.
     */
    protected function sendResponseJson($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Connecte l'utilisateur avec son Token
     * @return bool Si la connexion a été effectuée
     */
    public function checkToken(): bool
    {
        $validator = new Validator();
        return $validator->ValidateToken();
    }

    /**
     * Méthode pour envoyer une réponse sous forme de vue (HTML).
     * 
     * @param string $name Le nom de la vue à générer.
     * @param mixed $data Les données à passer à la vue.
     * @param string $langage Le langage de la vue (par défaut : 'en').
     * @param string $css Le fichier CSS à inclure dans la vue (par défaut : 'default.css').
     * @param bool $header Indique si l'en-tête doit être inclus (par défaut : true).
     * @param bool $footer Indique si le pied de page doit être inclus (par défaut : true).
     * @param int $status Le code de statut HTTP de la réponse (par défaut : 200).
     * 
     * Cette méthode génère une vue HTML basée sur les paramètres passés.
     */
    protected function sendResponseView($name, $data, $langage = 'en', $css = "default.css", bool $header = true, bool $footer = true, $status = 200)
    {
        $view = new View($name);
        $view->generer($data, $langage, $header, $footer, $css);
    }

    /**
     * Méthode abstraite à implémenter dans les classes enfants.
     * 
     * Chaque contrôleur concret doit implémenter cette méthode pour gérer les requêtes spécifiques à son rôle.
     */
    abstract public function handleRequest();

    /**
     * Exécuter une série de middlewares.
     * 
     * @param array $names Tableau contenant les noms des middlewares à exécuter.
     * @param mixed $paramRequest Les paramètres de la requête à passer aux middlewares.
     * @param array $params Paramètres additionnels à passer aux middlewares, par clé de nom de middleware.
     * 
     * @return mixed Le résultat final après exécution de tous les middlewares.
     * @throws CustomError Si une erreur survient lors de l'exécution d'un middleware.
     * 
     * Cette méthode cherche les fichiers des middlewares dans le répertoire `middleware`, les inclut et les exécute
     * dans l'ordre où ils sont spécifiés dans `$names`.
     */
    protected function runMiddlewares($classes, $paramRequest, $params)
    {

        try 
        {
            $res = $paramRequest;
            foreach ($classes as $class)
            {
                /* @type class fille de Middleware */
                $middleware = new $class();
                // Exécution du middleware avec les paramètres passés
                $res = $middleware->run($paramRequest, $params[$class] ?? null);
            }
            return $res;
        } 
        catch (CustomError $e) 
        {
            throw $e;
        }
    }
    /**
     * Redirige vers une URL spécifiée
     * @param string $uri L'URL vers laquelle rediriger
     */
    public function redirectToUri(string $url)
    {
        header("Location: $url ", true, 302); // 302 = Redirection temporaire
        exit; // Assurez-vous que l'exécution s'arrête après la redirection
    }
}
