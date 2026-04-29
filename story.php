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

<<<<<<< HEAD
$recommandations = [];
foreach ($stories as $s) {
    if ($s['categorie'] === $story_trouvee['categorie'] && $s['id'] !== $story_trouvee['id']) {
        $recommandations[] = $s;
    }
}
$recommandations = array_slice($recommandations, 0, 3);

=======
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
>>>>>>> 66998439b28dcf2446b5b11aad83e37617644e0a
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <title>Détail</title>
=======
    <title>Détail de la story</title>
>>>>>>> 66998439b28dcf2446b5b11aad83e37617644e0a
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

    <nav class="navbar">
        <div class="nav-left">
            <?php if(utilisateur_connecte()): ?>
                <span class="user-badge">
                    <span class="material-symbols-outlined">account_circle</span>
                    <?php echo htmlspecialchars(obtenir_utilisateur()["nom"]); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="nav-center">
            <a href="index.php" class="logo-link">
                <h1>Campus Stories</h1>
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

<<<<<<< HEAD
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
                        <span class="author-name"> <?php echo $rec["auteur"]; ?></span>
                        <a href="story.php?id=<?php echo $rec["id"]; ?>" class="view-more">Lire l'histoire →</a>
                    </div>
                </div>
                <?php endforeach; ?>
=======
<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" name="reaction" value="utile">
        Utile : <?php echo $story_trouvee["reactions"]["utile"]; ?>
    </button>

    <button type="submit" name="reaction" value="inspirant">
        Inspirant : <?php echo $story_trouvee["reactions"]["inspirant"]; ?>
    </button>

    <button type="submit" name="reaction" value="vecu_pareil">
        J’ai vécu pareil : <?php echo $story_trouvee["reactions"]["vecu_pareil"]; ?>
    </button>

    <button type="submit" name="reaction" value="bon_conseil">
        Bon conseil : <?php echo $story_trouvee["reactions"]["bon_conseil"]; ?>
    </button>

    <button type="submit" name="reaction" value="a_eviter">
        À éviter : <?php echo $story_trouvee["reactions"]["a_eviter"]; ?>
    </button>
</form>

<br>

<a href="index.php">Retour</a>
>>>>>>> 66998439b28dcf2446b5b11aad83e37617644e0a

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