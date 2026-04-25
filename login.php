<?php
// ============================
//  BACKEND AMADOU - LOGIN
// ============================

require_once 'includes/json_utils.php';
require_once 'includes/session.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    if ($email === "" || $mot_de_passe === "") {
        $message = "Tous les champs sont obligatoires.";
    } else {

        $chemin = 'data/users.json';
        $users = lire_json($chemin);

        $utilisateur_trouve = null;

        foreach ($users as $user) {
            if ($user["email"] === $email && $user["mot_de_passe"] === $mot_de_passe) {
                $utilisateur_trouve = $user;
                break;
            }
        }

        if ($utilisateur_trouve !== null) {
            connecter_utilisateur($utilisateur_trouve);
            header("Location: index.php");
            exit();
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============================== -->
<!--  FRONTEND LYSA - FORMULAIRE -->
<!-- ============================== -->

<nav class="navbar">
        <div class="nav-left">

        </div>

        <div class="nav-center">
            <a href="index.php" class="logo-link">
                <h1>Campus Stories</h1>
            </a>
        </div>

        <div class="menu nav-right">
           
        </div>
    </nav>

<h2>Connexion</h2>

<?php if ($message != ""): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email :</label><br>
    <input type="email" name="email"><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe"><br><br>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>