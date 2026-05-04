<?php
require_once 'includes/session.php';

$connecte = utilisateur_connecte();
$nom_utilisateur = "";

if ($connecte) {
    $nom_utilisateur = obtenir_utilisateur()["nom"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Stories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Campus Stories</h1>

<?php if ($connecte): ?>
    <p>Bienvenue, <?php echo $nom_utilisateur; ?> !</p>
    <p>
        <a href="create_story.php">Publier</a> |
        <a href="logout.php">Déconnexion</a>
    </p>
<?php else: ?>
    <p>
        <a href="login.php">Connexion</a> |
        <a href="register.php">Inscription</a>
    </p>
<?php endif; ?>

<hr>

<h2>Filtrer les expériences</h2>

<form id="filtre-form">
    <label>Catégorie :</label>
    <select id="categorie" name="categorie">
        <option value="">Toutes</option>
        <option value="Cours">Cours</option>
        <option value="Examens">Examens</option>
        <option value="Logement">Logement</option>
        <option value="Vie sur le campus">Vie sur le campus</option>
        <option value="Démarches administratives">Démarches administratives</option>
        <option value="Bons plans">Bons plans</option>
        <option value="Difficultés">Difficultés</option>
    </select>

    <label>Type :</label>
    <select id="type_experience" name="type_experience">
        <option value="">Tous</option>
        <option value="Témoignage">Témoignage</option>
        <option value="Conseil">Conseil</option>
        <option value="Alerte">Alerte</option>
        <option value="Bon plan">Bon plan</option>
        <option value="Erreur à éviter">Erreur à éviter</option>
        <option value="Expérience marquante">Expérience marquante</option>
    </select>

    <button type="submit">Filtrer</button>
    <button type="button" id="reset-filtre">Réinitialiser</button>
</form>

<hr>

<h2>Expériences</h2>

<div id="stories-container">
    <p>Chargement des stories...</p>
</div>

<script>
    const utilisateurConnecte = <?php echo $connecte ? "true" : "false"; ?>;
    const nomUtilisateur = "<?php echo $nom_utilisateur; ?>";
</script>

<script src="js/ajax.js"></script>

</body>
</html>