<?php
// =================================
//  BACKEND AMADOU - CREATE STORY
// =================================

require_once 'includes/json_utils.php';
require_once 'includes/session.php';

proteger_page();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST["titre"]);
    $contenu = trim($_POST["contenu"]);
    $categorie = trim($_POST["categorie"]);
    $type_experience = trim($_POST["type_experience"]);

    if ($titre === "" || $contenu === "" || $categorie === "" || $type_experience === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $chemin = 'data/stories.json';
        $stories = lire_json($chemin);

        $utilisateur = obtenir_utilisateur();

        $nouvelle_story = [
            "id" => generer_nouvel_id($stories),
            "titre" => $titre,
            "contenu" => $contenu,
            "categorie" => $categorie,
            "type_experience" => $type_experience,
            "auteur" => $utilisateur["nom"],
            "date" => date("Y-m-d H:i:s"),
            "reactions" => [
                "utile" => 0,
                "inspirant" => 0,
                "vecu_pareil" => 0,
                "bon_conseil" => 0,
                "a_eviter" => 0
            ]
        ];

        $stories[] = $nouvelle_story;
        ecrire_json($chemin, $stories);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <title>Campus Stories</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <?php if(utilisateur_connecte()): ?>
                <a href="profile.php" class="user-badge nav-link-badge" style="text-decoration:none;">
                    <span class="material-symbols-outlined">account_circle</span>
                    <?php echo htmlspecialchars(obtenir_utilisateur()["nom"]); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="nav-center">
            <a href="index.php" class="logo-link">
                <img src="images/logo.jpg" alt="Logo" class="logo-img">
                <span class="logo-text">Campus Stories</span>
            </a>
        </div>

        <div class="menu nav-right">
            <?php if(utilisateur_connecte()): ?>
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
    <form method="POST" action="create_story.php" class="login-form large-form">
        <h2>Publier une expérience</h2>
        
        <?php if ($message != ""): ?>
            <p style="color:#e74c3c; font-weight:bold; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <label>Titre</label>
        <input type="text" name="titre" placeholder="Entrez votre titre" required>
        
        <label>Votre story</label>
        <textarea name="contenu" rows="6" placeholder="Racontez votre expérience..." required></textarea>
        
        <label>Catégorie</label>
        <select name="categorie" required>
        <option value="">Choisir</option>
        <option value="Cours">Cours</option>
        <option value="Examens">Examens</option>
        <option value="Logement">Logement</option>
        <option value="Vie sur le campus">Vie sur le campus</option>
        <option value="Démarches administratives">Démarches administratives</option>
        <option value="Bons plans">Bons plans</option>
        <option value="Difficultés">Difficultés</option>
    </select>
    
    <label>Type d’expérience</label>
    <select name="type_experience" required>
        <option value="">Choisir</option>
        <option value="Témoignage">Témoignage</option>
        <option value="Conseil">Conseil</option>
        <option value="Alerte">Alerte</option>
        <option value="Bon plan">Bon plan</option>
        <option value="Erreur à éviter">Erreur à éviter</option>
        <option value="Expérience marquante">Expérience marquante</option>
    </select>

    <button type="submit" class="btn-filter">Publier</button>

    <p class="auth-switch">
        <a href="index.php" class="btn-back"> ← Retour à l'acceuil</a>
    </p>
</form></div>

</body>
</html>