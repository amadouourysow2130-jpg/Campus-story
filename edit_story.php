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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <?php if(utilisateur_connecte()): ?>
                <span class="user-badge">
                    <span class="material-symbols-outlined">account_circle</span>
                    <?php echo htmlspecialchars(obtenir_utilisateur()["nom"]); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="nav-center">
            <a href="index.php" class="logo-link">
                <h1>Campus Stories</h1>
            </a>
        </div>

        <div class="menu nav-right">
            <?php if(utilisateur_connecte()): ?>
                <a href="create_story.php" class="user-badge nav-link-badge">
                    <span class="material-symbols-outlined">add_circle</span>
                    Publier
                </a>
                <a href="logout.php" class="user-badge nav-link-badge logout-hover">
                    <span class="material-symbols-outlined">logout</span>
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="login.php" class="user-badge nav-link-badge">
                    <span class="material-symbols-outlined">login</span>
                    Connexion
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="reader-container">
        <form method="POST" class="login-form large-form">
            <h2>Modifier une expérience</h2>
            
            <?php if ($message != ""): ?>
                <p style="color:red;"><?php echo $message; ?></p>
            <?php endif; ?>
            
            <label>Titre</label>
            <input type="text" name="titre" value="<?php echo htmlspecialchars($story_trouvee["titre"]); ?>" required>
            
            <label>Votre Storie</label>
            <textarea name="contenu" rows="8" required><?php echo htmlspecialchars($story_trouvee["contenu"]); ?></textarea>

    <label>Catégorie</label>
    <select name="categorie" required>
                <?php 
                $cats = ["Cours", "Examens", "Logement", "Vie sur le campus", "Démarches administratives", "Bons plans", "Difficultés"];
                foreach($cats as $c) {
                    $sel = ($story_trouvee["categorie"] === $c) ? "selected" : "";
                    echo "<option value=\"$c\" $sel>$c</option>";
                }
                ?>
    </select>

    <label>Type d’expérience</label><br>
    <select name="type_experience" required>
        <?php 
        $types = ["Témoignage", "Conseil", "Alerte", "Bon plan", "Erreur à éviter", "Expérience marquante"];
        foreach($types as $t) {
            $sel = ($story_trouvee["type_experience"] === $t) ? "selected" : "";
            echo "<option value=\"$t\" $sel>$t</option>";
        }
        ?>
        </select>

    <button type="submit">Enregistrer les modifications</button>

    <p class="auth-switch">
        <a href="story.php?id=<?php echo $id; ?>">Annuler</a>
    </p>
</form> </div>
</body>
</html>