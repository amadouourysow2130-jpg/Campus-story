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
<html>
<head>
    <title>Détail de la story</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1><?php echo $story_trouvee["titre"]; ?></h1>

<p>
    Auteur : <?php echo $story_trouvee["auteur"]; ?><br>
    Catégorie : <?php echo $story_trouvee["categorie"]; ?><br>
    Type : <?php echo $story_trouvee["type_experience"]; ?><br>
    Date : <?php echo $story_trouvee["date"]; ?>
</p>

<p><?php echo $story_trouvee["contenu"]; ?></p>

<hr>

<h3>Réactions</h3>

<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" name="reaction" value="utile">
        Utile : <span id="reaction-utile"><?php echo $story_trouvee["reactions"]["utile"]; ?></span>
    </button>

    <button type="submit" name="reaction" value="inspirant">
        Inspirant : <span id="reaction-inspirant"><?php echo $story_trouvee["reactions"]["inspirant"]; ?></span>
    </button>

    <button type="submit" name="reaction" value="vecu_pareil">
        J’ai vécu pareil : <span id="reaction-vecu-pareil"><?php echo $story_trouvee["reactions"]["vecu_pareil"]; ?></span>
    </button>

    <button type="submit" name="reaction" value="bon_conseil">
        Bon conseil : <span id="reaction-bon-conseil"><?php echo $story_trouvee["reactions"]["bon_conseil"]; ?></span>
    </button>

    <button type="submit" name="reaction" value="a_eviter">
        À éviter : <span id="reaction-a-eviter"><?php echo $story_trouvee["reactions"]["a_eviter"]; ?></span>
    </button>
</form>

<br>

<a href="index.php">Retour</a>

<script>
const storyId = <?php echo $story_trouvee["id"]; ?>;

function mettreAJourReactions() {
    fetch("api/get_story.php?id=" + storyId)
        .then(response => response.json())
        .then(story => {
            if (!story) return;

            document.getElementById("reaction-utile").textContent = story.reactions.utile;
            document.getElementById("reaction-inspirant").textContent = story.reactions.inspirant;
            document.getElementById("reaction-vecu-pareil").textContent = story.reactions.vecu_pareil;
            document.getElementById("reaction-bon-conseil").textContent = story.reactions.bon_conseil;
            document.getElementById("reaction-a-eviter").textContent = story.reactions.a_eviter;
        })
        .catch(error => {
            console.error("Erreur AJAX story :", error);
        });
}

setInterval(mettreAJourReactions, 5000);
</script>

</body>
</html>