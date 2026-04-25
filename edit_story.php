<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

proteger_page();

$chemin = 'data/stories.json';
$stories = lire_json($chemin);

if (!isset($_GET["id"])) {
    echo "Story introuvable.";
    exit();
}

$id = $_GET["id"];
$utilisateur = obtenir_utilisateur();

$story_trouvee = null;
$index = null;
$message = "";

// Chercher la story
foreach ($stories as $i => $story) {
    if ($story["id"] == $id) {
        $story_trouvee = $story;
        $index = $i;
        break;
    }
}

if ($story_trouvee === null) {
    echo "Story non trouvée.";
    exit();
}

// Vérifier que la story appartient bien à l’utilisateur connecté
if ($story_trouvee["auteur"] !== $utilisateur["nom"]) {
    echo "Vous ne pouvez pas modifier cette story.";
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST["titre"]);
    $contenu = trim($_POST["contenu"]);
    $categorie = trim($_POST["categorie"]);
    $type_experience = trim($_POST["type_experience"]);

    if ($titre === "" || $contenu === "" || $categorie === "" || $type_experience === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $stories[$index]["titre"] = $titre;
        $stories[$index]["contenu"] = $contenu;
        $stories[$index]["categorie"] = $categorie;
        $stories[$index]["type_experience"] = $type_experience;

        ecrire_json($chemin, $stories);

        header("Location: story.php?id=" . $id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier une story</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Modifier une expérience</h2>

<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Titre :</label><br>
    <input type="text" name="titre" value="<?php echo $story_trouvee["titre"]; ?>"><br><br>

    <label>Contenu :</label><br>
    <textarea name="contenu"><?php echo $story_trouvee["contenu"]; ?></textarea><br><br>

    <label>Catégorie :</label><br>
    <select name="categorie">
        <option value="Cours" <?php if ($story_trouvee["categorie"] === "Cours") echo "selected"; ?>>Cours</option>
        <option value="Examens" <?php if ($story_trouvee["categorie"] === "Examens") echo "selected"; ?>>Examens</option>
        <option value="Logement" <?php if ($story_trouvee["categorie"] === "Logement") echo "selected"; ?>>Logement</option>
        <option value="Vie sur le campus" <?php if ($story_trouvee["categorie"] === "Vie sur le campus") echo "selected"; ?>>Vie sur le campus</option>
        <option value="Démarches administratives" <?php if ($story_trouvee["categorie"] === "Démarches administratives") echo "selected"; ?>>Démarches administratives</option>
        <option value="Bons plans" <?php if ($story_trouvee["categorie"] === "Bons plans") echo "selected"; ?>>Bons plans</option>
        <option value="Difficultés" <?php if ($story_trouvee["categorie"] === "Difficultés") echo "selected"; ?>>Difficultés</option>
    </select><br><br>

    <label>Type d’expérience :</label><br>
    <select name="type_experience">
        <option value="Témoignage" <?php if ($story_trouvee["type_experience"] === "Témoignage") echo "selected"; ?>>Témoignage</option>
        <option value="Conseil" <?php if ($story_trouvee["type_experience"] === "Conseil") echo "selected"; ?>>Conseil</option>
        <option value="Alerte" <?php if ($story_trouvee["type_experience"] === "Alerte") echo "selected"; ?>>Alerte</option>
        <option value="Bon plan" <?php if ($story_trouvee["type_experience"] === "Bon plan") echo "selected"; ?>>Bon plan</option>
        <option value="Erreur à éviter" <?php if ($story_trouvee["type_experience"] === "Erreur à éviter") echo "selected"; ?>>Erreur à éviter</option>
        <option value="Expérience marquante" <?php if ($story_trouvee["type_experience"] === "Expérience marquante") echo "selected"; ?>>Expérience marquante</option>
    </select><br><br>

    <button type="submit">Enregistrer</button>
</form>

<p><a href="index.php">Retour</a></p>

</body>
</html>