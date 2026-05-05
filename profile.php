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

    <main id="feed_stories">
        <?php if (empty($mes_stories)): ?>
            <p>Vous n'avez encore publié aucune story.</p>
        <?php else: ?>
            <?php foreach ($mes_stories as $story): 

                $search = ['é','è','ê','ë','à','â','î','ï','ô','û','ù','ç',' '];
                $replace = ['e','e','e','e','a','a','i','i','o','u','u','c','-'];
                $nom_propre = str_replace($search,$replace, strtolower(trim($story["categorie"])));
                $nom_propre = preg_replace('/[^a-z0-9]+/', '-', $nom_propre);
                $nom_propre = trim($nom_propre, '-');
                
                $classe_categorie = 'cat-' . $nom_propre;
            ?>

            <a href="story.php?id=<?php echo $story['id']; ?>" class="story-card">
                <div class="card-content">

                    <span class="category-badge <?php echo $classe_categorie; ?>">
                        <?php echo mb_strtoupper($story["categorie"], 'UTF-8'); ?>
                    </span>

                    <h3><?php echo htmlspecialchars($story["titre"]); ?></h3>

                    <p>
                        <?php echo htmlspecialchars(substr($story["contenu"], 0, 100)); ?>...
                    </p>

                    <div class="story-card-footer">
                        <span class="post-date">
                            <?php echo date("d/m/Y", strtotime($story["date"])); ?>
                        </span>
                    </div>

                </div>
            </a>

            <?php endforeach; ?>
        <?php endif; ?>
    </main>

</div>

</body>
</html>