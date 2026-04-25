<?php
require_once 'includes/session.php';
proteger_page();
?>

<?php
// =================================
//  BACKEND AMADOU - CREATE STORY
// =================================

require_once 'includes/json_utils.php';
require_once 'includes/session.php';

proteger_page();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST["titre"]);
    $contenu = trim($_POST["contenu"]);
    $categorie = trim($_POST["categorie"]);
    $type_experience = trim($_POST["type_experience"]);

    if ($titre === "" || $contenu === "" || $categorie === "" || $type_experience === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $chemin = 'data/stories.json';
        $stories = lire_json($chemin);

        $utilisateur = obtenir_utilisateur();

        $nouvelle_story = [
            "id" => generer_nouvel_id($stories),
            "titre" => $titre,
            "contenu" => $contenu,
            "categorie" => $categorie,
            "type_experience" => $type_experience,
            "auteur" => $utilisateur["nom"],
            "date" => date("Y-m-d H:i:s"),
            "reactions" => [
                "utile" => 0,
                "inspirant" => 0,
                "vecu_pareil" => 0,
                "bon_conseil" => 0,
                "a_eviter" => 0
            ]
        ];

        $stories[] = $nouvelle_story;
        ecrire_json($chemin, $stories);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Publier une expérience</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============================== -->
<!--  FRONTEND LYSA - FORMULAIRE -->
<!-- ============================== -->

<h2>Publier une expérience</h2>

<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Titre :</label><br>
    <input type="text" name="titre"><br><br>

    <label>Contenu :</label><br>
    <textarea name="contenu"></textarea><br><br>

    <label>Catégorie :</label><br>
    <select name="categorie">
        <option value="">Choisir</option>
        <option value="Cours">Cours</option>
        <option value="Examens">Examens</option>
        <option value="Logement">Logement</option>
        <option value="Vie sur le campus">Vie sur le campus</option>
        <option value="Démarches administratives">Démarches administratives</option>
        <option value="Bons plans">Bons plans</option>
        <option value="Difficultés">Difficultés</option>
    </select><br><br>

    <label>Type d’expérience :</label><br>
    <select name="type_experience">
        <option value="">Choisir</option>
        <option value="Témoignage">Témoignage</option>
        <option value="Conseil">Conseil</option>
        <option value="Alerte">Alerte</option>
        <option value="Bon plan">Bon plan</option>
        <option value="Erreur à éviter">Erreur à éviter</option>
        <option value="Expérience marquante">Expérience marquante</option>
    </select><br><br>

    <button type="submit">Publier</button>
</form>

</body>
</html>