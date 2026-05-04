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

//prendre le top des stories les plus utiles
$stories_top = lire_json($chemin);

usort($stories_top, function($a, $b) {
    $utiles_a =isset($a["reactions"]["utile"]) ? $a["reactions"]["utile"] : 0;
    $utiles_b =isset($b["reactions"]["utile"]) ? $b["reactions"]["utile"] : 0;
    return $utiles_b - $utiles_a;
});

$top_5 = array_slice($stories_top,0,5);

?>

<!DOCTYPE html>
<html lang="fr">
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
    
    <div class="hero-section">
        <section class="banner-story">
            <div class="banner-text">
                <h2 class="banner-title">
                    PARTAGEZ VOTRE<br><span>EXPÉRIENCE CAMPUS</span>
                </h2>
                <p class="banner-label">
                    <strong>Histoires, conseils, anecdotes</strong><br>
                    retrouvez les stories de vos camarades et contribuez à la communauté
                </p>
                
                <?php if (utilisateur_connecte()): ?>
                    <a href="create_story.php" class="btn-add">+ AJOUTER UNE STORY</a>
                <?php else: ?>
                    <a href="register.php" class="btn-add">REJOINDRE LA COMMUNAUTÉ</a>
                <?php endif; ?>
            </div>

            <div class="banner-image">
                <img src="images/hero.png" alt="Illustration campus">
            </div>

        </section>

        <aside class="top-stories">
            <h3>
                <span class="material-symbols-outlined icon-title">emoji_events</span>
                LES PLUS UTILES
            </h3>
            <div class="top-list">
                <?php foreach ($top_5 as $index => $s): ?>
                    <a href="story.php?id=<?php echo $s['id']; ?>" class="top-item">
                        <span class="rank">#<?php echo $index + 1; ?></span>
                        <div class="top-info">
                            <p class="top-title"><?php echo htmlspecialchars($s['titre']); ?></p>
                            <span class="top-meta"><?php echo $s['reactions']['utile']; ?> utiles</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>

<div class="glass-container">

 <section class="filter-bar">
    <div class="grid-header">
        <h2 class="grid-title">Grille de Stories</h2>
        <div class="title-underline"></div>
    </div>

    <form method="GET" class="filter-form">
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
        <button type="submit" class="btn-filter"><span class="material-symbols-outlined filter-icon">filter_list</span>Filtrer</button>
    </div>
    </form>
</section>

    <main id="stories-container">
        <?php if (empty($stories_filtrees)): ?>
            <p>Aucune story trouvée.</p>
        <?php else: ?>
            <?php foreach ($stories_filtrees as $story): 
                $search = ['é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù', 'ç', ' '];
                $replace = ['e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u', 'c', '-'];
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
                
                    <p class="story-excerpt"><?php echo htmlspecialchars(substr($story["contenu"], 0, 100)); ?>...</p>
                    
                    <div class="story-card-footer">
                        <div class="user-info">
                            <div class="author-block">
                                <span class="material-symbols-outlined author-icon">account_circle</span>
                                <span class="author-name"><?php echo htmlspecialchars($story["auteur"]); ?></span>
                            </div>
                            
                            <?php if (utilisateur_connecte() && obtenir_utilisateur()["nom"] === $story["auteur"]): ?>
                                <div class="author-actions">
                                    <span class="action-link" onclick="event.preventDefault(); window.location.href='edit_story.php?id=<?php echo $story['id']; ?>';">Modifier</span>
                                    <span class="action-link delete" onclick="event.preventDefault(); window.location.href='delete_story.php?id=<?php echo $story['id']; ?>';">Supprimer</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div></div></a>
            <?php endforeach; ?>
        <?php endif; ?></main>

    <script>
        var utilisateurConnecte = <?php echo utilisateur_connecte() ? 'true' : 'false'; ?>;
        var nomUtilisateur = "<?php echo (utilisateur_connecte()) ? obtenir_utilisateur()['nom'] : ''; ?>";
    </script>

    <script src="js/ajax.js"></script>
    <script src="js/main.js"></script>
</body>
</body>
</html>