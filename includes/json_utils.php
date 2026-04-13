<?php

function lire_json($chemin_fichier) {
    if (!file_exists($chemin_fichier)) {
        return [];
    }

    $contenu = file_get_contents($chemin_fichier);

    if ($contenu === false || trim($contenu) === '') {
        return [];
    }

    $donnees = json_decode($contenu, true);

    if (!is_array($donnees)) {
        return [];
    }

    return $donnees;
}

function ecrire_json($chemin_fichier, $donnees) {
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        return false;
    }

    return file_put_contents($chemin_fichier, $json) !== false;
}

function generer_nouvel_id($tableau) {
    if (empty($tableau)) {
        return 1;
    }

    $ids = array_column($tableau, 'id');

    if (empty($ids)) {
        return 1;
    }

    return max($ids) + 1;
}
?>
