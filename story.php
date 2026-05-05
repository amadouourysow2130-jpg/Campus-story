<?php
require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$chemin = 'data/stories.json';
$stories = lire_json($chemin);

if (!isset($_GET["id"])) {
    echo "Story introuvable.";
    exit();
}

$id = (int) $_GET["id"];
$story = null;
$index = null;

foreach ($stories as $i => $s) {
    if ((int)$s["id"] === $id) {
        $story = $s;
        $index = $i;
        break;
    }
}

if ($story === null) {
    echo "Story non trouvée.";
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!utilisateur_connecte()) {
        $message = "Vous devez être connecté pour réagir.";
    } else {
        $reaction = $_POST["reaction"];
        $user = obtenir_utilisateur();
        $user_id = $user["id"];

        if (!isset($stories[$index]["reacted_users"])) {
            $stories[$index]["reacted_users"] = [
                "utile" => [],
                "inspirant" => [],
                "vecu_pareil" => [],
                "bon_conseil" => [],
                "a_eviter" => []
            ];
        }

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

$story = $stories[$index];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $story["titre"]; ?> - Campus Stories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <?php if (utilisateur_connecte()): ?>
            <span class="user-badge"><?php echo obtenir_utilisateur()["nom"]; ?></span>
        <?php endif; ?>
    </div>

    <div class="nav-center">
        <a href="index.php" class="logo-link">
            <span class="logo-text">Campus Stories</span>
        </a>
    </div>

    <div class="menu nav-right">
        <?php if (utilisateur_connecte()): ?>
            <a href="create_story.php">Publier</a>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<main style="max-width:900px; margin:50px auto; background:white; color:#0f172a; padding:35px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">

    <h1 style="color:#d35400; font-size:32px; margin-bottom:20px;">
        <?php echo $story["titre"]; ?>
    </h1>

    <p style="color:#334155; font-size:15px;">
        <strong>Auteur :</strong> <?php echo $story["auteur"]; ?><br>
        <strong>Catégorie :</strong> <?php echo $story["categorie"]; ?><br>
        <strong>Type :</strong> <?php echo $story["type_experience"]; ?><br>
        <strong>Date :</strong> <?php echo $story["date"]; ?>
    </p>

    <hr>

    <p style="font-size:18px; line-height:1.8; color:#0f172a;">
        <?php echo nl2br($story["contenu"]); ?>
    </p>

    <hr>

    <h3 style="color:#0f172a;">Réactions</h3>

    <?php if ($message !== ""): ?>
        <p style="color:red; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" style="display:flex; flex-wrap:wrap; gap:10px;">
        <button type="submit" name="reaction" value="utile">Utile : <?php echo $story["reactions"]["utile"]; ?></button>
        <button type="submit" name="reaction" value="inspirant">Inspirant : <?php echo $story["reactions"]["inspirant"]; ?></button>
        <button type="submit" name="reaction" value="vecu_pareil">Pareil : <?php echo $story["reactions"]["vecu_pareil"]; ?></button>
        <button type="submit" name="reaction" value="bon_conseil">Bon conseil : <?php echo $story["reactions"]["bon_conseil"]; ?></button>
        <button type="submit" name="reaction" value="a_eviter">À éviter : <?php echo $story["reactions"]["a_eviter"]; ?></button>
    </form>

    <br>
    <a href="index.php" style="color:#0f172a; font-weight:bold;">← Retour</a>

</main>

</body>
</html>