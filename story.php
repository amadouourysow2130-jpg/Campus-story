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
$index = null;

foreach ($stories as $i => $story) {
    if ($story["id"] == $id) {
        $story_trouvee = $story;
        $index = $i;
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


/* Création de reacted_users si elle n'existe pas encore */
if (!isset($stories[$index]["reacted_users"])) {
    $stories[$index]["reacted_users"] = [
        "utile" => [],
        "inspirant" => [],
        "vecu_pareil" => [],
        "bon_conseil" => [],
        "a_eviter" => []
    ];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!utilisateur_connecte()) {
        $message = "Vous devez être connecté pour réagir.";
    } else {
        $reaction = $_POST["reaction"];
        $utilisateur = obtenir_utilisateur();
        $user_id = $utilisateur["id"];

        if (isset($stories[$index]["reactions"][$reaction])) {

            if (in_array($user_id, $stories[$index]["reacted_users"][$reaction])) {
                $message = "Vous avez déjà choisi cette réaction.";
            } else {
                $stories[$index]["reactions"][$reaction]++;
                $stories[$index]["reacted_users"][$reaction][] = $user_id;

                ecrire_json($chemin, $stories);

                header("Location: story.php?id=" . $id);
                exit();
            }
        }
    }
}

$story_trouvee = $stories[$index];

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($story_trouvee["titre"]); ?> - Campus Stories</title>
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

    <main class="reader-container"> 
        <div class="story-full-card">
            <div class="card-header-flex">

                <div class="header-left header-inline">
                    <span class="material-symbols-outlined author-icon">account_circle</span> 
                    <span class="author-name">
                        <?php echo htmlspecialchars($story_trouvee["auteur"]); ?>
                    </span>
                    <span class="post-date">
                        <?php echo $story_trouvee["date"]; ?>
                    </span>
                </div>

                <div class="header-right"> 
                    <?php
                    $search =['é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù', 'ç', ' ']; 
                    $replace = ['e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u', 'c', '-'];
                    $classe_cat = 'cat-' . str_replace($search, $replace, strtolower($story_trouvee["categorie"]));
                    ?>
                    <span class="category-badge <?php echo $classe_cat; ?>">
                        <?php echo mb_strtoupper(htmlspecialchars($story_trouvee["categorie"]), 'UTF-8'); ?> 
                    </span> 
                </div> 
            </div>

            <h1 class="story-title-main"><?php echo htmlspecialchars($story_trouvee["titre"]); ?></h1>

            <div class="story-content"> 
                <p><?php echo nl2br(htmlspecialchars($story_trouvee["contenu"])); ?></p>
            </div>

            <hr class="separator">
            
            <h3>Réactions</h3>
            <?php if ($message != ""): ?>
                <p style="color:#e74c3c; font-weight:bold; margin-bottom:10px;"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST" id="story-reactions">
                <button type="submit" name="reaction" value="bon_conseil" class="btn-reaction">
                    <span class="material-symbols-outlined">tips_and_updates</span>
                    <span>Bon conseil</span>
                    <span class="count"><?php echo $story_trouvee["reactions"]["bon_conseil"]; ?></span>
                </button>
                <button type="submit" name="reaction" value="inspirant" class="btn-reaction">
                    <span class="material-symbols-outlined">lightbulb</span>
                    <span>Inspirant</span>
                    <span class="count"><?php echo $story_trouvee["reactions"]["inspirant"]; ?></span>
                </button>
                <button type="submit" name="reaction" value="utile" class="btn-reaction">
                    <span class="material-symbols-outlined">thumb_up</span>
                    <span>Utile</span>
                    <span class="count"><?php echo $story_trouvee["reactions"]["utile"]; ?></span>
                </button>                
                <button type="submit" name="reaction" value="vecu_pareil" class="btn-reaction">
                    <span class="material-symbols-outlined">groups</span>
                    <span>Pareil</span>
                    <span class="count"><?php echo $story_trouvee["reactions"]["vecu_pareil"]; ?></span>
                </button>
                <button type="submit" name="reaction" value="a_eviter" class="btn-reaction">
                    <span class="material-symbols-outlined">warning</span>
                    <span>À éviter</span>
                    <span class="count"><?php echo $story_trouvee["reactions"]["a_eviter"]; ?></span>
                </button>
            </form>
        
        <br>
        <a href="index.php" class="btn-back"> ← Retour</a>
    </div>
</main>

<div class="glass-container suuggestions-bg">
<section class="suggestions-section">
    <h2 class="suggestions-title">À lire aussi</h2>


    <div class="stories-grid-suggestions">
        <?php foreach ($recommandations as $rec) :
        $search =['é', 'è', 'à', 'ç',' ']; $replace = ['e', 'e', 'a', 'c', '-'];
        $classe_cat = 'cat-' . str_replace($search, $replace, strtolower($rec["categorie"]));
        ?>
        <a href="story.php?id=<?php echo $rec['id']; ?>" class="story-card">
            <span class="category-badge <?php echo $classe_cat; ?>"><?php echo mb_strtoupper($rec["categorie"], 'UTF-8'); ?></span>
            <h3><?php echo htmlspecialchars($rec["titre"]); ?></h3>
            <p class="story-excerpt"><?php echo htmlspecialchars(substr($rec["contenu"],0,80)); ?>...</p>

           <div class="story-card-footer">
            <div class="user-info">
                <div class="author-block">
                    <span class="material-symbols-outlined author-icon">account_circle</span>
                    <span class="author-name"><?php echo htmlspecialchars($rec["auteur"]); ?></span>
                </div>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
    
    <?php if (empty($recommandations)) : ?>
        <p style="color: #2c3e50; opacity: 0.5; text-align: center; width: 100%;">Aucune suggestion pour le moment.</p>
        <?php endif; ?>
    </div> </section> 
</div>
</body>
</html>