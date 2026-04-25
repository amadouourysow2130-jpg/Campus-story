<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

proteger_page();

if (!isset($_GET["id"])) {
    echo "Story introuvable.";
    exit();
}

$id = $_GET["id"];
$chemin = 'data/stories.json';
$stories = lire_json($chemin);

$utilisateur = obtenir_utilisateur();
$stories_mises_a_jour = [];
$story_trouvee = false;
$suppression_autorisee = false;

foreach ($stories as $story) {
    if ($story["id"] == $id) {
        $story_trouvee = true;

        // Vérifier que l’utilisateur connecté est l’auteur
        if ($story["auteur"] === $utilisateur["nom"]) {
            $suppression_autorisee = true;
            continue; // on n’ajoute pas cette story => supprimée
        } else {
            echo "Vous ne pouvez pas supprimer cette story.";
            exit();
        }
    }

    $stories_mises_a_jour[] = $story;
}

if (!$story_trouvee) {
    echo "Story non trouvée.";
    exit();
}

if ($suppression_autorisee) {
    ecrire_json($chemin, $stories_mises_a_jour);
}

header("Location: index.php");
exit();
?>