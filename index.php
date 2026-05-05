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

    <form method="GET" class="filter-form" id="filtre-form">
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

    <main style="max-width:1200px; margin:30px auto; display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:25px; padding:0 20px;">

<?php if (empty($stories_filtrees)): ?>
    <p>Aucune story trouvée.</p>
<?php else: ?>
    <?php foreach ($stories_filtrees as $story): ?>

        <a href="story.php?id=<?php echo $story['id']; ?>" 
           style="background:#0f172a; color:white; text-decoration:none; padding:20px; border-radius:12px; min-height:260px; display:flex; flex-direction:column; justify-content:space-between;">

            <div>
                <?php
                $categorie = strtolower($story["categorie"]);

                $couleur = "#d35400"; // défaut

                if ($categorie == "examens") $couleur = "#e74c3c";
                elseif ($categorie == "cours") $couleur = "#3498db";
                elseif ($categorie == "logement") $couleur = "#e67e22";
                elseif ($categorie == "vie sur le campus") $couleur = "#2ecc71";
                elseif ($categorie == "bons plans") $couleur = "#f1c40f";
                elseif ($categorie == "démarches administratives") $couleur = "#9b59b6";
                elseif ($categorie == "difficultés") $couleur = "#7f8c8d";
                ?>

                <span style="
                    display:inline-block;
                    padding:4px 12px;
                    border-radius:20px;
                    font-size:12px;
                    font-weight:bold;
                    background: <?php echo $couleur; ?>20;
                    color: <?php echo $couleur; ?>;
                ">
                    <?php echo strtoupper($story["categorie"]); ?>
                </span>

                <h3 style="color:#d35400; margin-top:15px;">
                    <?php echo $story["titre"]; ?>
                </h3>

                <p style="color:white;">
                    <?php echo substr($story["contenu"], 0, 100); ?>...
                </p>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.2); padding-top:15px; margin-top:20px;">
                👤 <?php echo $story["auteur"]; ?>

                <?php if (utilisateur_connecte() && obtenir_utilisateur()["nom"] === $story["auteur"]): ?>
                    <br>
                    <span onclick="event.preventDefault(); window.location.href='edit_story.php?id=<?php echo $story['id']; ?>';" style="color:#d35400;">Modifier</span>
                    |
                    <span onclick="event.preventDefault(); window.location.href='delete_story.php?id=<?php echo $story['id']; ?>';" style="color:#ff7675;">Supprimer</span>
                <?php endif; ?>
            </div>

        </a>

    <?php endforeach; ?>
<?php endif; ?>

</main>

    <script>
        var utilisateurConnecte = <?php echo utilisateur_connecte() ? 'true' : 'false'; ?>;
        var nomUtilisateur = "<?php echo (utilisateur_connecte()) ? obtenir_utilisateur()['nom'] : ''; ?>";
    </script>

  
    
</body>
</html>