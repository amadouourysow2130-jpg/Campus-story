<?php 
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

proteger_page();

$utilisateur = obtenir_utilisateur();
$stories = lire_json('data/stories.json');

// récupérer les stories de l'utilisateur
$mes_stories = [];
$total_reactions = 0;

foreach ($stories as $story) {
    if ($story["auteur"] === $utilisateur["nom"]) {
        $mes_stories[] = $story;

        // compter toutes les réactions
        foreach ($story["reactions"] as $reaction) {
            $total_reactions += $reaction;
        }
    }
}

$nb_stories = count($mes_stories);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

<body>
<nav class="navbar">
    <div class="nav-left">
        
    </div>

    <div class="nav-center">
        <a href="index.php" class="logo-link">
            <img src="images/logo.jpg" class="logo-img">
            <span class="logo-text">Campus Stories</span>
        </a>
    </div>

    <div class="menu nav-right">
        <a href="create_story.php" class="user-badge nav-link-badge">
            <span class="material-symbols-outlined">add_circle</span>
            Publier
        </a>
        <a href="logout.php" class="user-badge nav-link-badge">
            <span class="material-symbols-outlined">logout</span>
            Déconnexion
        </a>
    </div>
</nav>

<div class="glass-container">

    <section class="profile-header">
        <div class="profile-left">
            <span class="material-symbols-outlined profile-icon">account_circle</span>
            <h2 class="profile-name"><?php echo htmlspecialchars($utilisateur["nom"]); ?></h2>
        </div>

        <div class="profile-stats">
            <div class="stat-box">
                <span class="stat-number"><?php echo $nb_stories; ?></span>
                <span class="stat-label">Stories</span>
            </div>

            <div class="stat-box">
                <span class="stat-number"><?php echo $total_reactions; ?></span>
                <span class="stat-label">Réactions</span>
            </div>
        </div>
    </section>

    <section class="filter-bar">
        <div class="grid-header">
            <h2 class="grid-title">Mes Stories</h2>
            <div class="title-underline"></div>
        </div>
    </section>

    <main style="max-width:1200px; margin:30px auto; display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:25px; padding:0 20px;">

        <?php foreach ($mes_stories as $story): ?>
            <a href="story.php?id=<?php echo $story['id']; ?>"
            style="background:#0f172a; color:white; text-decoration:none; padding:20px; border-radius:12px; min-height:260px; display:flex; flex-direction:column; justify-content:space-between;">

            <div>
                

             <h3 style="color:#d35400;">
                <?php echo $story["titre"]; ?>
             </h3>

             <p style="color:white;">
                <?php echo substr($story["contenu"], 0, 100); ?>...
             </p>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.2); padding-top:15px;">
        <?php echo $story["categorie"]; ?>
        </div>
    </a>
<?php endforeach; ?>

</main>

</div>

</body>
</html>