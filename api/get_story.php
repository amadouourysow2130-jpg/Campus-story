<?php
require_once '../includes/json_utils.php';

$chemin = '../data/stories.json';
$stories = lire_json($chemin);

if (!isset($_GET["id"])) {
    echo json_encode(null);
    exit();
}

$id = $_GET["id"];
$story_trouvee = null;

foreach ($stories as $story) {
    if ($story["id"] == $id) {
        $story_trouvee = $story;
        break;
    }
}

header('Content-Type: application/json');
echo json_encode($story_trouvee);
?>