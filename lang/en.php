<?php

function getTraductionTab()
{
    return [
        'user' => [
            'id' => 'ID: ',
            'firstname' => 'Prénom: ',
            'lastname' => 'Nom: ',
            'email' => 'Email: ',
            'phone' => 'Téléphone: ',
            'date_creation' => 'Date de création: ',
            'deniere_connexion' => 'Dernière connexion: ',
            'active' => 'Actif: ',
            'none' => 'Aucun utilisateur trouvé.',
            'profileid' => 'ID du profil: ',
        ],
        'forfait' => [
            'id' => 'ID: ',
            'name' => 'Nom: ',
            'description' => 'Description: ',
            'none' => 'Aucun forfait trouvé.',
        ],
        'reservations' => [
            'id' => 'ID: ',
            'name' => 'Nom: ',
            'description' => 'Description: ',
            'none' => 'Aucune Disponibilitées trouvée.',
        ],
        'success' => [
            'delete' => [
                'user' => 'Utilisateur supprimé avec succès'
            ]
                ],
        'errors' => [
            'request' => 'Bad Request:',
            'checkParameters' => [
                'requierd' => 'parameter is required',
                'login' => 'Invalid identifier'
            ],
            '404' => [
                'label' => ' 404 Not Found',
            ],
            '401' => [
                'label' => ' 401 Logout Unauthorized'
            ],
            'delete' => [
                'user' => 'Erreur lors de la suppresion de l\'Utilisateur'
            ]
        ]
    ];
}


function getTraduction(string $path): string|null
{
    $keys = explode('.', $path); // Séparer les clés à partir du chemin
    $res = getTraductionTab(); // Commencer par le tableau principal
    // Parcourir les clés pour accéder à la valeur souhaitée
    foreach ($keys as $key) {
        if (isset($res[$key])) {

            $res = $res[$key]; // Descendre d'un niveau dans le tableau
        } else {
            return null; // Si la clé n'existe pas, retourner null
        }
    }
    // Si la valeur trouvée est une chaîne, la retourner
    return is_string($res) ? $res : null;
}