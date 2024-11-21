<?php
/**
 * Représente une vue, définie par son titre et son chemin de fichier relatif
 */
class View
{
    private string $fichier;
    private string $titre;

    /**
     * Construit le vue
     * @param string $action L'action de la vue, ex : pour la vue "vueAddTask" l'action sera "AddTask"
     */
    public function __construct(string $action)
    {
        // Détermination du nom du fichier vue à partir de l'action
        $this->fichier = "views/vue" . $action . ".php";
        $this->titre = "Agence - " . $action;
    }

    /**
     * Génère et affiche la vue avec le gabarit
     * @param array $donnees Tableau associatif contenant les différentes données à afficher dans la vue
     */
    public function generer(array $donnees, string $language, bool $header, bool $footer, string $css)
    {
        // Génération de la partie spécifique de la vue
        $contenu = $this->genererFichier($this->fichier, $donnees);

        // Génération du gabarit commun utilisant la partie spécifique
        $vue = $this->genererFichier(
            'views/gabarit.php',
            array(
                'language' => $language,
                'header' => $header,
                'footer' => $footer,
                'css' => $css,
                'title' => $this->titre,
                'contenu' => $contenu
            )
        );

        // Renvoi de la vue au navigateur
        echo $vue;
    }

    /**
     * Génère un fichier vue et renvoie le résultat produit
     * @param string $fichier Le chemin relatif de la vue à générer
     * @param array $donnees Tableau associatif contenant les différentes données à afficher dans la vue
     * @return string Le contenu du fichier généré avec les données remplies à l'intérieur
     *  */
    private function genererFichier(string $fichier, array $donnees): string
    {
        if (file_exists($fichier)) {
            // Rend les éléments du tableau $donnees accessibles dans la vue
            // Voir la documentation de extract
            extract($donnees);

            // Démarrage de la temporisation de sortie
            ob_start();

            // Inclut le fichier vue
            // Son résultat est placé dans le tampon de sortie
            require $fichier;

            // Arrêt de la temporisation et renvoi du tampon de sortie
            return ob_get_clean();
        } else {
            throw new Exception("Fichier '$fichier' introuvable");
        }
    }
}
?>