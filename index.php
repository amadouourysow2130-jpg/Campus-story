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
    if ($ok_categorie && $ok_type) { $stories_filtrees[] = $story; }
}

// Trier par date décroissante
usort($stories_filtrees, function($a, $b) {
    return strtotime($b["date"]) - strtotime($a["date"]);
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Campus Stories</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">

        </div>

        <div class="nav-center">
            <a href="index.php" class="logo-link">
                <h1>Campus Stories</h1>
            </a>
        </div>

        <div class="menu nav-right">
            <?php if(utilisateur_connecte()): ?>
                <a href="create_story.php">Publier</a>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="login.php">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if (utilisateur_connecte()): ?>
        <p style="text-align:center; color:white;">Bienvenue, <?php echo obtenir_utilisateur()["nom"]; ?> !</p>
    <?php endif; ?>


    <section class="banner-story">
        <div class="banner-content">
            <h2 class="banner-title">PARTAGEZ VOTRE<br><span>EXPÉRIENCE CAMPUS</span></h2>

            <p class="banner-label"><bold>Histoires, conseils, anecdotes</bold>
            <br>retrouvez les stories de vos camarades et contribuez à la communauté.
        </p>

            <a href="create_story.php" class="btn-add">+      AJOUTER UNE STORY</a>

        </div>
    </section>

 <section class="filter-bar">
    <h2 class="grid-title">Grille de Stories</h2>
    <form method="GET" class="filter-form">
        <label for="categorie" class="filter-label"></label>
        <div class="filter-group">
            <select name="categorie" id="categorie">
                <option value="">Toutes les catégories</option>
                <option value="Cours" <?php if ($categorie_filtre === "Cours") echo "selected"; ?>>Cours</option>
                <option value="Examens" <?php if ($categorie_filtre === "Examens") echo "selected"; ?>>Examens</option>
                <option value="Logement" <?php if ($categorie_filtre === "Logement") echo "selected"; ?>>Logement</option>
                <option value="Vie sur le campus" <?php if ($categorie_filtre === "Vie sur le campus") echo "selected"; ?>>Vie sur le campus</option>
                <option value="Démarches administratives" <?php if ($categorie_filtre === "Démarches administratives") echo "selected"; ?>>Démarches administratives</option>
                <option value="Bons plans" <?php if ($categorie_filtre === "Bons plans") echo "selected"; ?>>Bons plans</option>
                <option value="Difficultés" <?php if ($categorie_filtre === "Difficultés") echo "selected"; ?>>Difficultés</option>
        </select>
        <button type="submit" class="btn-filter">Filtrer</button>
    </div>
    </form>
</section>

    <main id="feed_stories">
        <?php if (empty($stories_filtrees)): ?>
            <p>Aucune story trouvée.</p>
        <?php else: ?>
            <?php foreach ($stories_filtrees as $story): 
            $classe_categorie = 'cat-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $story["categorie"])));
            ?>
            
            <div class="story-card">
                <span class="category-badge <?php echo $classe_categorie; ?>">
                    <?php echo strtoupper($story["categorie"]); ?>
                </span>
                
                <h3><?php echo $story["titre"]; ?></h3>
                
                <p class="story-excerpt"><?php echo substr($story["contenu"], 0, 100); ?>...</p>
                
                <div class="story-card-footer">
                    <div class="user-info">
                        <span class="author-name"><?php echo $story["auteur"]; ?></span>
                        
                        <?php if (utilisateur_connecte() && obtenir_utilisateur()["nom"] === $story["auteur"]): ?>
                            <div class="author-actions">
                                <a href="edit_story.php?id=<?php echo $story["id"]; ?>">Modifier</a> | 
                                <a href="delete_story.php?id=<?php echo $story["id"]; ?>" style="color:red;">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <a href="story.php?id=<?php echo $story["id"]; ?>" class="view-more">Voir plus -></a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?></main>

    <script src="java/ajax.js"></script>
    <script src="java/main.js"></script>
</body>
</html>