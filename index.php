<?php require_once 'includes/session.php' ; ?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Campus Stories</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>

<body>
    <nav class="navbar">
        <h1>Campus Stories</h1>
        <div class="menu">
            <a href="index.php">Accueil</a>
            <?php if(utilisateur_connecte()): ?>
                <a href="create_story.php">Publier</a>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="login.php">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>

    <main id="feed_stories"> <!-- afficher les histoires -->
        <p>Chargement des expériences et histoires...</p>
    </main>

    <script src="js/ajax.js"></script> <!-- fichier ajax.js relié mais peut être pas necessaire ? -->
    <script src="js/main.js"></script> <!-- fichier java a relier plus tard -->

</body>
</html>