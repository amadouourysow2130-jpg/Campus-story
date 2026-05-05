<?php
// ===============================
//  BACKEND AMADOU - INSCRIPTION
// ===============================a

require_once 'includes/json_utils.php';
require_once 'includes/session.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    // Vérification des champs
    if ($nom === "" || $email === "" || $mot_de_passe === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {

        $chemin = 'data/users.json';

        // Lire les utilisateurs existants
        $users = lire_json($chemin);

        // Vérifier si l'email existe déjà
        foreach ($users as $user) {
            if ($user["email"] === $email) {
                $message = "Cet email est déjà utilisé.";
                break;
            }
        }

        // Si tout est bon
        if ($message === "") {

            $nouvel_user = [
                "id" => generer_nouvel_id($users),
                "nom" => $nom,
                "email" => $email,
                "mot_de_passe" => $mot_de_passe
            ];

            // Ajouter dans le tableau
            $users[] = $nouvel_user;

            // Sauvegarder dans JSON
            ecrire_json($chemin, $users);

            // Connexion automatique
            connecter_utilisateur($nouvel_user);

            // Redirection
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
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

    <div class="reader-container"> <form method="POST" action="register.php" class="login-form">
        <h2>Créer un compte</h2>

        <?php if (isset($message) && $message != ""): ?>
            <p style="color:#e74c3c; font-weight:bold; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <label>Nom d'utilisateur</label>
        <input type="text" name="nom" required placeholder="Votre nom ou pseudo">

        <label>Email</label>
        <input type="email" name="email" required placeholder="votre@email.com">
        
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required placeholder="........">

        <button type="submit">S'inscrire</button>

        <p class="auth-switch">
            Déjà un compte ? <a href="login.php">Connectez-vous ici</a>
        </p>
    </form></div>
</body>
</html>