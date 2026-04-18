<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$chemin = 'data/stories.json';
$stories = lire_json($chemin);

// Récupérer les filtres
$categorie_filtre = isset($_GET["categorie"]) ? trim($_GET["categorie"]) : "";
$type_filtre = isset($_GET["type_experience"]) ? trim($_GET["type_experience"]) : "";

// Appliquer les filtres
$stories_filtrees = [];

foreach ($stories as $story) {
    $ok_categorie = ($categorie_filtre === "" || $story["categorie"] === $categorie_filtre);
    $ok_type = ($type_filtre === "" || $story["type_experience"] === $type_filtre);

    if ($ok_categorie && $ok_type) {
        $stories_filtrees[] = $story;
    }
}

// Trier par date décroissante
usort($stories_filtrees, function($a, $b) {
    return strtotime($b["date"]) - strtotime($a["date"]);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Stories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Campus Stories</h1>

<?php if (utilisateur_connecte()): ?>
    <p>Bienvenue, <?php echo obtenir_utilisateur()["nom"]; ?> !</p>
    <p>
        <a href="create_story.php">Publier</a> |
        <a href="logout.php">Déconnexion</a>
    </p>
<?php else: ?>
    <p>
        <a href="login.php">Connexion</a> |
        <a href="register.php">Inscription</a>
    </p>
<?php endif; ?>

<hr>

<h2>Filtrer les expériences</h2>

<form method="GET">
    <label>Catégorie :</label>
    <select name="categorie">
        <option value="">Toutes</option>
        <option value="Cours" <?php if ($categorie_filtre === "Cours") echo "selected"; ?>>Cours</option>
        <option value="Examens" <?php if ($categorie_filtre === "Examens") echo "selected"; ?>>Examens</option>
        <option value="Logement" <?php if ($categorie_filtre === "Logement") echo "selected"; ?>>Logement</option>
        <option value="Vie sur le campus" <?php if ($categorie_filtre === "Vie sur le campus") echo "selected"; ?>>Vie sur le campus</option>
        <option value="Démarches administratives" <?php if ($categorie_filtre === "Démarches administratives") echo "selected"; ?>>Démarches administratives</option>
        <option value="Bons plans" <?php if ($categorie_filtre === "Bons plans") echo "selected"; ?>>Bons plans</option>
        <option value="Difficultés" <?php if ($categorie_filtre === "Difficultés") echo "selected"; ?>>Difficultés</option>
    </select>

    <label>Type :</label>
    <select name="type_experience">
        <option value="">Tous</option>
        <option value="Témoignage" <?php if ($type_filtre === "Témoignage") echo "selected"; ?>>Témoignage</option>
        <option value="Conseil" <?php if ($type_filtre === "Conseil") echo "selected"; ?>>Conseil</option>
        <option value="Alerte" <?php if ($type_filtre === "Alerte") echo "selected"; ?>>Alerte</option>
        <option value="Bon plan" <?php if ($type_filtre === "Bon plan") echo "selected"; ?>>Bon plan</option>
        <option value="Erreur à éviter" <?php if ($type_filtre === "Erreur à éviter") echo "selected"; ?>>Erreur à éviter</option>
        <option value="Expérience marquante" <?php if ($type_filtre === "Expérience marquante") echo "selected"; ?>>Expérience marquante</option>
    </select>

    <button type="submit">Filtrer</button>
    <a href="index.php">Réinitialiser</a>
</form>

<hr>

<h2>Expériences</h2>

<?php if (empty($stories_filtrees)): ?>
    <p>Aucune story trouvée.</p>
<?php else: ?>
    <?php foreach ($stories_filtrees as $story): ?>
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">
            <h3><?php echo $story["titre"]; ?></h3>

            <p>
                Auteur : <?php echo $story["auteur"]; ?><br>
                Catégorie : <?php echo $story["categorie"]; ?><br>
                Type : <?php echo $story["type_experience"]; ?><br>
                Date : <?php echo $story["date"]; ?>
            </p>

            <p><?php echo $story["contenu"]; ?></p>

            <a href="story.php?id=<?php echo $story["id"]; ?>">Voir plus</a>
            <?php if (utilisateur_connecte() && obtenir_utilisateur()["nom"] === $story["auteur"]): ?>
                <a href="delete_story.php?id=<?php echo $story["id"]; ?>">Supprimer</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>