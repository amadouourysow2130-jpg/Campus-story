<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$chemin = 'data/stories.json';
$stories = lire_json($chemin);

if (!isset($_GET["id"])) {
    echo "Story introuvable.";
    exit();
}

$id = $_GET["id"];
$story_trouvee = null;

foreach ($stories as $story) {
    if ($story["id"] == $id) {
        $story_trouvee = $story;
        break;
    }
}

if ($story_trouvee === null) {
    echo "Story non trouvée.";
    exit();
}

$recommandations = [];
foreach ($stories as $s) {
    if ($s['categorie'] === $story_trouvee['categorie'] && $s['id'] !== $story_trouvee['id']) {
        $recommandations[] = $s;
    }
}
$recommandations = array_slice($recommandations, 0, 3);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail</title>
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

    <main class="reader-container"> 
        <div class="story-full-card"> <h1><?php echo $story_trouvee["titre"]; ?></h1>
        
        <div class="meta-data">
            <p><strong>Auteur :</strong> <?php echo $story_trouvee["auteur"]; ?></p>
            <p><strong>Catégorie :</strong> <?php echo $story_trouvee["categorie"]; ?> | <strong>Type :</strong> <?php echo $story_trouvee["type_experience"]; ?></p>
            <p><small>Posté le <?php echo $story_trouvee["date"]; ?></small></p>
        </div>
        
        <div class="story-content">
            <p><?php echo nl2br($story_trouvee["contenu"]); ?></p>
        </div>
        
        <hr>
        
        <h3>Réactions</h3>
        <div class="reactions-container">
            <button class="btn-reaction"> Utile : (<?php echo $story_trouvee["reactions"]["utile"]; ?>)</button>
            <button class="btn-reaction"> Inspirant : (<?php echo $story_trouvee["reactions"]["inspirant"]; ?>)</button>
            <button class="btn-reaction"> Pareil : (<?php echo $story_trouvee["reactions"]["vecu_pareil"]; ?>)</button>
            <button class="btn-reaction"> Bon conseil : (<?php echo $story_trouvee["reactions"]["bon_conseil"]; ?>)</button>
            <button class="btn-reaction"> À éviter : (<?php echo $story_trouvee["reactions"]["a_eviter"]; ?>)</button>
        </div>
        
        <br>
        <a href="index.php" class="btn-back"> ← Retour</a>
    </div>
</main>

<div class="glass-container">
<section class="suggestions-section">
    <h2 class="suggestions-title">À lire aussi</h2>

    <div class="stories-grid-suggestions">
        <?php foreach ($recommandations as $rec) :
        $classe_cat = 'cat-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $rec["categorie"])));
        ?>
        <div class="story-card">
            <span class="category-badge <?php echo $classe_cat; ?>">
                <?php echo strtoupper($rec["categorie"]); ?>
            </span>

        <h3><?php echo $rec["titre"]; ?></h3>
                    <p class="story-excerpt"><?php echo substr($rec["contenu"], 0, 80); ?>...</p>
                    
                    <div class="story-card-footer">
                        <span class="author-name">👤 <?php echo $rec["auteur"]; ?></span>
                        <a href="story.php?id=<?php echo $rec["id"]; ?>" class="view-more">Lire l'histoire →</a>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (empty($recommandations)) : ?>
                    <p style="color: white; opacity: 0.5; text-align: center; width: 100;">
                        Aucune autre histoire dans cette catégorie pour le moment.
                    </p>
                <?php endif; ?>
            </div>
</section>
</div>
</body>
</html>