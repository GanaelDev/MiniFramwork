<?php

function getTraductionTab()
{
    return [
        'user' => [
            'id' => 'ID: ',
            'name' => 'Nom: ',
            'email' => 'Email: ',
            'none' => 'Aucun utilisateur trouvé.',
        ],
        'errors' => [
            'request' => 'Bad Request:',
            'checkParameters' => [
                'requierd' => 'parameter is required',
                'login' =>  'Invalid identifier'
            ],
            '404' => [
                'label' => ' 404 Not Found',
            ],
            '401' => [
                'label' => ' 401 Logout Unauthorized'
            ]
        ]
    ];
}

function getTraduction(string $path) : string|null
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