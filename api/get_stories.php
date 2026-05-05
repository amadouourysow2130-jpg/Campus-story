<?php
require_once '../includes/json_utils.php';

$stories = lire_json('../data/stories.json');

if (!isset($_GET["id"])) {
    echo json_encode(null);
    exit();
}

$id = (int) $_GET["id"];
$story_trouvee = null;

foreach ($stories as $story) {
    if ((int)$story["id"] === $id) {
        $story_trouvee = $story;
        break;
    }
}

header('Content-Type: application/json');
echo json_encode($story_trouvee);
?>