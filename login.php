<?php
// ============================
//  BACKEND AMADOU - LOGIN
// ============================

require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    if ($email === "" || $mot_de_passe === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {

        $chemin = 'data/users.json';
        $users = lire_json($chemin);

        $utilisateur_trouve = null;

        foreach ($users as $user) {
            if ($user["email"] === $email && $user["mot_de_passe"] === $mot_de_passe) {
                $utilisateur_trouve = $user;
                break;
            }
        }

        if ($utilisateur_trouve !== null) {
            connecter_utilisateur($utilisateur_trouve);
            header("Location: index.php");
            exit();
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
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

    <div class="reader-container"> <form method="POST" action="login.php" class="login-form">
        <h2>Connexion</h2>
        
        <?php if (isset($message) && $message != ""): ?>
            <p style="color:#e74c3c; font-weight:bold; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <label>Email</label>
        <input type="email" name="email" required placeholder="votre@email.com">
        
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required placeholder="........">
        
        <button type="submit">Se connecter</button>

        <p class="auth-switch">
            Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
        </p>
    </form></div>
</body>
</html>