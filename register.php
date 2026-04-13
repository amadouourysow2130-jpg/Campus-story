<?php
// ===============================
// 🔵 BACKEND AMADOU - INSCRIPTION
// ===============================

require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    // Vérification des champs
    if ($nom === "" || $email === "" || $mot_de_passe === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {

        $chemin = 'data/users.json';

        // Lire les utilisateurs existants
        $users = lire_json($chemin);

        // Vérifier si l'email existe déjà
        foreach ($users as $user) {
            if ($user["email"] === $email) {
                $message = "Cet email est déjà utilisé.";
                break;
            }
        }

        // Si tout est bon
        if ($message === "") {

            $nouvel_user = [
                "id" => generer_nouvel_id($users),
                "nom" => $nom,
                "email" => $email,
                "mot_de_passe" => $mot_de_passe
            ];

            // Ajouter dans le tableau
            $users[] = $nouvel_user;

            // Sauvegarder dans JSON
            ecrire_json($chemin, $users);

            // Connexion automatique
            connecter_utilisateur($nouvel_user);

            // Redirection
            header("Location: index.php");
            exit();
        }
    }
}
?>

//Lysa fronted

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Créer un compte</h2>

<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom"><br><br>

    <label>Email :</label><br>
    <input type="email" name="email"><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe"><br><br>

    <button type="submit">S'inscrire</button>
</form>

</body>
</html>