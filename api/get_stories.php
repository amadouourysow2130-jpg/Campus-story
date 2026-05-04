<?php
require_once '../includes/json_utils.php';

$chemin = '../data/stories.json';
$stories = lire_json($chemin);

$categorie_filtre = isset($_GET["categorie"]) ? trim($_GET["categorie"]) : "";
$type_filtre = isset($_GET["type_experience"]) ? trim($_GET["type_experience"]) : "";

$stories_filtrees = [];

foreach ($stories as $story) {
    $ok_categorie = ($categorie_filtre === "" || $story["categorie"] === $categorie_filtre);
    $ok_type = ($type_filtre === "" || $story["type_experience"] === $type_filtre);

    if ($ok_categorie && $ok_type) {
        $stories_filtrees[] = $story;
    }
}

usort($stories_filtrees, function($a, $b) {
    return strtotime($b["date"]) - strtotime($a["date"]);
});

header('Content-Type: application/json');
echo json_encode($stories_filtrees);
?>