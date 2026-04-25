<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$chemin = 'data/stories.json';
$stories = lire_json($chemin);

if (!isset($_GET["id"])) {
    echo "Story introuvable.";
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

if ($story_trouvee === null) {
    echo "Story non trouvée.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Détail</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1><?php echo $story_trouvee["titre"]; ?></h1>

<p>
    Auteur : <?php echo $story_trouvee["auteur"]; ?><br>
    Catégorie : <?php echo $story_trouvee["categorie"]; ?><br>
    Type : <?php echo $story_trouvee["type_experience"]; ?><br>
    Date : <?php echo $story_trouvee["date"]; ?>
</p>

<p><?php echo $story_trouvee["contenu"]; ?></p>

<hr>

<h3>Réactions</h3>

<p>
    Utile : <?php echo $story_trouvee["reactions"]["utile"]; ?><br>
    Inspirant : <?php echo $story_trouvee["reactions"]["inspirant"]; ?><br>
    Vécu pareil : <?php echo $story_trouvee["reactions"]["vecu_pareil"]; ?><br>
    Bon conseil : <?php echo $story_trouvee["reactions"]["bon_conseil"]; ?><br>
    À éviter : <?php echo $story_trouvee["reactions"]["a_eviter"]; ?>
</p>

<a href="index.php">← Retour</a>

</body>
</html>