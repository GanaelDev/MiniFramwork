<?php
require_once("./controllers/ControllerLoader.php");
class Router
{
    // Méthode principale de l'exécution du routeur
    public function run()
    {
        // Obtenir l'URL demandée et enlever le préfixe si nécessaire
        $uri = $this->getRequestUri();
        
        // Si aucune route spécifiée, rediriger vers 'home'
        $uri = $this->getDefaultUri($uri);
        $language = "en";

        try {
            // Chargement dynamique du contrôleur
            ControllerLoader::load_one($uri);
            $controllerClass = ControllerLoader::getClassForRoute($uri);
            if($controllerClass != null)
            {
                $controller = new $controllerClass($language);
                $controller->handleRequest();
            }
            exit();
        } catch (CustomError $e) {
            $this->handleCustomError($e);
            exit();
        }
        exit();
    }

    // Récupère l'URI sans préfixe
    private function getRequestUri(): string
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $prefix = $this->getProjectPrefix();

        if (strpos($uri, $prefix) === 0) {
            $uri = substr($uri, strlen($prefix));
        }
        
        return trim($uri, '/'); // Nettoyage des slashes
    }

    // Retourne le préfixe du projet
    private function getProjectPrefix(): string
    {
        return str_replace('%5C', '/', str_replace('+', '%20', urlencode(explode('htdocs\\', getcwd())[1])));
    }

    // Définit la route par défaut si aucune n'est spécifiée
    private function getDefaultUri(string $uri): string
    {
        return empty($uri) ? 'home' : $uri;
    }

    // Gestion des erreurs personnalisées
    private function handleCustomError(CustomError $e)
    {
        switch ($e->getType()) {
            case Error401Unauthorized::class:
                $this->redirectToLogout();
                break;
            case Error404NotFound::class:
                $this->notFound();
                break;
            case ErrorcheckParameters::class:
                $this->redirectToCurrentUriWithError($e->getMessage());
                break;
            default:
                $this->serverError();
                break;
        }
    }

    // Redirection vers la page de déconnexion
    private function redirectToLogout()
    {
        $uri = 'logout';
        $language = "en";
        ControllerLoader::load_one($uri);
        $controllerClass = ControllerLoader::getClassForRoute($uri);
        $controller = new $controllerClass($language);;
        $controller->handleRequest();
    }

    // Gestion de la page 404
    protected function notFound()
    {
        var_dump('404');
        http_response_code(404);
        $this->redirectToLogout();

    }

    // Gestion de l'erreur serveur (500)
    protected function serverError()
    {
        http_response_code(500);
        echo "<br/>500 - Erreur interne du serveur";
    }
    // Redirige vers le chemin actuel avec un message d'erreur
    private function redirectToCurrentUriWithError(string $errorMessage)
    {
        // Récupère l'URL actuelle sans paramètres de requête existants
        $currentUri = strtok($_SERVER['REQUEST_URI'], '?');
        // Construit l'URL de redirection avec le message d'erreur
        $redirectUri = $currentUri . '?errors=' . urlencode($errorMessage);
        // Redirige vers l'URL avec le code de statut 302 (redirection temporaire)
        header("Location: $redirectUri", true, 302);
    }
}

// Démarrer le routeur
$router = new Router();
$router->run();
