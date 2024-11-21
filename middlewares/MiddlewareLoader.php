<?php

// Si on veut aller plus vite fauddrait juste faire un switch case et tout remplir au fur et à mesure mais j'ai la flemme
class MiddlewareLoader
{
    public static function load_many( array $listMiddlewares)
    {
        foreach($listMiddlewares as $nameMiddleware)
        {
            self::load_one($nameMiddleware);
        }
    }

    public static function load_one(string $name)
    {
        $middlewareFile = '';
        $nametemp = $name . '.php';
        $dir = new DirectoryIterator(__DIR__ . '/');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $nametemp == $fileinfo->getFilename()) {
                $middlewareFile = $fileinfo->getPathname();
            }
        }
        if (file_exists($middlewareFile)) {
            // Inclure le fichier middleware correspondant
            require_once $middlewareFile;
        }
    }

}

?>